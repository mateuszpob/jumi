<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model{
    
    protected $table = 'carts';
    
//    public static function find($id){
//        $c = \App\Cart::where('id', $id)->first();
//        $c->calculateShipmentPrice();
//        return $c;
//    }

    public function items(){
        return $this->belongsToMany('App\Item', 'cart_items');
    }
    public function cartItems(){
        return $this->hasMany('App\CartItem')->active();
    }
    
	// return total quantity active the same item in cart
    private function itemIsInCart($item_id, $variant_id=null){
    	$items_in_cart = \App\CartItem::where('cart_id', $this->id)
                                    ->where('item_id', $item_id)
                                    ->where('variant_id', $variant_id)
                                    ->active()
                                    ->take(1)->get();
    	
    	if(empty($items_in_cart->all()))
            return 0;
    	else
            return $items_in_cart->all()[0]->quantity;
    }
    /*
     * Pobiera cart_itemy (nie itemy) znajdujace sie w tym koszyku.
     */
    public function getCartItems(){
        return \App\CartItem::where('cart_id', $this->id)->active()->noDeleted()->get();
    }
    /*
     * Pobiera obiekty itemow ktore sa w carcie
     */
    public function getItemsInCart(){
        $cart_items = \App\CartItem::select('item_id')->where('cart_id', $this->id)->active()->noDeleted()->get()->all();
        $items = array();
        foreach($cart_items as $cart_item){
            $items[] = \App\Item::find($cart_item->item_id)->active()->get()->all();
        }
        
        return $items;
    }
    /*
     * Pobiera liste (nazwa, liczba, cena) itemow w koszyku
     * Do uzcia w koszyku w menu i w stronie koszyka.
     */
    public function getItemInCartNameList(){

        return \DB::table('cart_items')
                ->select('items.id as item_id', 'items.image_path', 'items.name', 'cart_items.id', 'cart_items.quantity', 'cart_items.price', 'cart_items.price_discounted')
                ->leftJoin('items', 'items.id', '=', 'cart_items.item_id')
                ->where('cart_items.cart_id', $this->id)
                ->where('cart_items.deleted_at', null)
                ->get();
        
    }
    /*
     * Zwraca liczbe pozycji w koszyku (bez wysylki)
     */
    public function getCartQuantity(){
    	return \App\CartItem::where('cart_id', $this->id)->active()->noDeleted()->count();
    }
    /*
     * Pobiera liczbe itemow w koszyku (całkowita, jesli jednego itemu jest kilka sztuk, to sie sumuje)
     */
    public function getCartQuantityTotal(){
    	return \App\CartItem::where('cart_id', $this->id)->active()->noDeleted()->sum('quantity');
    }
    /*
     * Dodajke item bezwariantowy do karta
     */
    public function addItemToCart($item_id, $variant_id=null){
    	
        $item = \App\Item::findOrFail((int)$item_id);
        $variant = \App\ProductVariant::find($variant_id);
        
        // check if item is in cart - get quantity
        $item_quantity = $this->itemIsInCart($item_id, $variant_id);

        // sprawdz czy item wa warianty, gdy wariant niepodany
        if($variant === null && $item->variants()->count()){
            // jak ma to spierdalaj
            return array('has_variants'=>true);
        }
        

        // dodaj pierwszy item do karta
        if($item_quantity == 0){
        	$cart_item = new \App\CartItem;

        	$cart_item->cart_id = $this->id;
        	$cart_item->item_id = $item_id;
        	$cart_item->quantity = 1;

            // jesli item ma warianty to dodaj
            if($variant){
                $cart_item->variant_id = $variant->id;
                $cart_item->ean = $variant->ean;
                $cart_item->price = $variant->getPrice();
                $cart_item->price_discounted = $variant->getPriceDiscounted();
            }else{
                $cart_item->ean = $item->ean;
                $cart_item->price = $item->getPrice();
                $cart_item->price_discounted = $item->getPriceDiscounted();
            }

        	$cart_item->save();
        }else{// jesli item juz jest w karcie
            $item_quantity++;
            // jesli item ma warianty to dodaj
            if($variant){
                $cart_item = \App\CartItem::where('cart_id', $this->id)
                        ->where('item_id', $item_id)
                        ->where('variant_id', $variant->id)
                        ->active()
                        ->update(['quantity' => (int)$item_quantity]);
            }else{
        	    // jesli item nie ma wariantow
        	    $cart_item = \App\CartItem::where('cart_id', $this->id)
        				->where('item_id', $item_id)
        				->active()
        				->update(['quantity' => (int)$item_quantity]);
            }
        }
        // return actual cart quantity for update cart icon
        return array('has_variants'=>false, 'quantity'=>$this->getCartQuantity());
    }
//    /*
//     * Pobiera liczbe itemow w koszyku (całkowita, jesli jednego itemu jest kilka sztuk, to sie sumuje)
//     */
//    public function getTotalQuantity(){
//        // tak moze byc, ale to trzeba potem php zsumowac
//        // $d = \App\CartItem::select('sum(quantity)')->where('cart_id', $this->id)->active()->noDeleted()->get();
//        
//        // wiec lepiej zrobic zapytanie
//        $d = \DB::table('cart_items')
//                ->select(\DB::raw('sum(quantity) as total_quantity'))
//                ->where('active', true)
//                ->where('deleted_at', null)
//                ->get();
//        return isset($d[0]) ? $d[0]->total_quantity : 0;
//    }
    /*
     * Pobiera calkowita wartosc itemow w koszyku
     */
    public function getTotalAmount(){
        $d = \DB::table('cart_items')
                ->select(\DB::raw('price, price_discounted, quantity'))
                ->where('cart_id', $this->id)
                ->where('active', true)
                ->where('deleted_at', null)
                ->get();
        
        $total_amount = 0;
        foreach($d as $row){
            if($row->price_discounted)
                $total_amount += ($row->price_discounted * $row->quantity);
            else
                $total_amount += ($row->price * $row->quantity);
        }
        return isset($d[0]) ? $total_amount : 0;
    }
    /*
     * Ustaw koszyk (id koszyka wez z sesji) DO zalogowanego usera.
     * Potrzebne gdy user niezalogowany w trakcie czekałtu przypomni
     * sobie, że jednak ma konto, to ten cart zostanie przypisany do konta
     */
    public static function setCurrentCartToLoggedUser(){
        if(\Session::has('cart_id') && \Auth::check()){
            $cart = \App\Cart::noChecked()->active()->findOrFail(\Session::get('cart_id'));
            $cart->user_id = \Auth::id();
            $cart->save();
        }
    }
    
    public function getTotalWeight(){
//        $d = \DB::table('cart_items')
//                ->select(\DB::raw('price, quantity'))
//                ->where('cart_id', $this->id)
//                ->where('active', true)
//                ->where('deleted_at', null)
//                ->get();
        
        return 10;
    }
    
    public function checkoutCart(){
        $this->active = false;
        $this->checked_date = new \DateTime('now');
        $this->save();
        
        // wywal koszyk i forme przesylki z sesji
        \Session::forget('cart_id');
        \Session::forget('shipment_id');
        \Session::save();
    }
    
    /*
     * Oblicza cene wysylki aktywnych przedmiotow w koszyku
     */
    public function calculateShipmentPrice(){
        $total_shipment_amount = 0;
        $items = $this->cartItems;
        $items_groups = [];
        foreach($items as $item){
            $item->item->quantity = $item->quantity;
            $items_groups[$item->item->shipment_id][] = $item->item;
        }
        foreach($items_groups as $shipment_id => $item_g){
            $shipment = \App\Shipment::find((int)$shipment_id);
            if(!$shipment)
                continue;
//            echo 's id: '.$shipment->id.'</br>';
            
            switch(true){
                case $shipment->price_percentage == null && $shipment->price > 0 && !$shipment->quantity_multiplication && $shipment->max_quantity == null:
//                    echo '(100 zeta)</br>';
                    $total_shipment_amount += $shipment->price;
                    break;
                case $shipment->price_percentage > 0 && $shipment->max_quantity == null:
                    $gr_items_amount = 0;
                    foreach($item_g as $part_item){
                        $gr_items_amount += $part_item->quantity * round(($part_item->price_discounted ? $part_item->price_discounted : $part_item->price), 2);
                    }
//                    echo '('.$shipment->price_percentage.'% od '.$gr_items_amount.')</br>';
                    $total_shipment_amount += round(($gr_items_amount * $shipment->price_percentage / 100), 2);
                    break;
                case $shipment->price_percentage == null && $shipment->price > 0 && $shipment->quantity_multiplication && $shipment->max_quantity == null://dd($item_g);
                    $gr_items_count = 0;
                    foreach($item_g as $part_item){
                        $gr_items_count += $part_item->quantity;
                    }
//                    echo '('.$gr_items_count.' razy 49 zeta)</br>';
                    $total_shipment_amount += round($shipment->price * $gr_items_count, 2);
                    break;
                // kilka itemow w jednej paczce (max_quantity)
                case $shipment->price_percentage == null && $shipment->price > 0 && $shipment->quantity_multiplication && $shipment->max_quantity > 0://dd($item_g);
                    $gr_items_count = 0;
                    foreach($item_g as $part_item){
                        $gr_items_count += $part_item->quantity;
                    }
                    $total_shipment_amount += round($shipment->price * ceil($gr_items_count / $shipment->max_quantity), 2);
                    break;
            }
        }
        return $total_shipment_amount;
//        echo '$total_shipment_amount: '.$total_shipment_amount.'</br>';
//        exit;
//        return 1;
    }
    

    /*
     * Podlicz cenę wysyłki itemów ktore są w carcie
     */
    public function calculateShipmentPrice_(){
        $total_shipment_amount = 0;
        $items = $this->cartItems;
        $items_groups = [];
        foreach($items as $item){
            $item->item->quantity = $item->quantity;
            $items_groups[$item->item->shipment_id][] = $item->item;
        }
        $r = '';
        
        foreach($items_groups as $shipment_id => $item_g){
            $shipment = \App\Shipment::find($shipment_id);
            
            if($shipment->price_percentage > 0){
                $r .= 'a';
                
                foreach($item_g as $item){
                    $items_amount = 0;
                    if($item->price_discounted)
                        $items_amount += ($item->price_discounted * $item->quantity);
                    else
                        $items_amount += ($item->price * $item->quantity2);
                }
                echo 'a: '.round(($items_amount*($shipment->price_percentage)/100), 2).'</br>';
                $total_shipment_amount += round(($items_amount*($shipment->price_percentage)/100), 2);
            }elseif($shipment->price_percentage == null && $shipment->price > 0 && $shipment->quantity_multiplication){
                $r .= 'b';
                $total_shipment_amount += ($item->quantity * $shipment->price);
            }elseif($shipment->price_percentage == null && $shipment->price > 0 && !$shipment->quantity_multiplication){
                $r .= 'c'; echo 'c: '.$shipment->price.'</br>';
                $total_shipment_amount += $shipment->price;//dd('huj 2', $total_shipment_amount);
            }
            
            
            /*
            switch(true){
                // wartosc wysylki = suma wartosci przedmiotow razy procent
                case $shipment->price_percentage > 0:
                    $r .= 'a';
                    $items_amount = 0;
                    foreach($item_g as $item){
                        if($item->price_discounted)
                            $items_amount += ($item->price_discounted * $item->quantity);
                        else
                            $items_amount += ($item->price * $item->quantity);
                    }
                    $total_shipment_amount += round(($items_amount*($shipment->price_percentage)/100), 2);
                    
                    break;
                // stała cena mnożona razy ilosc przedmiotów
                case $shipment->price_percentage == null && $shipment->price > 0 && $shipment->quantity_multiplication:
                    $r .= 'b';
                    $total_shipment_amount += ($item->quantity * $shipment->price);
                    
                    break;
                // stala cena niezaleznie od liczby przedmiotów
                case $shipment->price_percentage == null && $shipment->price > 0 && !$shipment->quantity_multiplication:
                    $r .= 'c';
                    $total_shipment_amount += $shipment->price;//dd('huj 2', $total_shipment_amount);
                    break;
            }
             
             */
        }
//        dd($r, 'huj', $total_shipment_amount);
        $this->shipment_price = $total_shipment_amount;
        return $total_shipment_amount;
    }
    public function scopeActive($query){
        return $query->where('carts.active', true);
    }
    
    public function scopeNoChecked($query){
        return $query->where('carts.checked_date', null);
    }
    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
     public function scopeSessionCookie($query, $cookie_session_id)
    {
        return $query->where('carts.cookie_session_id', $cookie_session_id);
    }
    
    
    
    
    
    
    
    
    
    
    
    public static function getCartCookie() {
        if ($cid = \Cookie::get('cid', false)) {
            return $cid;
        } elseif ($cid = \Session::get('cid', false)) {
            return $cid;
        } else {
            $cid = time() . str_random(25);
            $cookie = \Cookie::make('cid', $cid, 60 * 24 * 30);
            \Cookie::queue($cookie);
        }
        return $cid;
    }
    
    /**
     * Zwraca aktualny koszyk użytkownika.
     * Jeżeli użytkownik jest zalogowany to wykorzystuje ID użytkownika,
     * w innym przypadku korzysta z sessionCookieId.
     * Jeżeli użytkownik nie posiada aktywnego koszyka wynikiem będzie null.
     */
    public static function getCurrentCart() {
        if (\Auth::check()) {
            $cart = self::user(\Auth::id())->active()
                ->noChecked()
                ->orderBy('id', 'desc')
                ->with('items')
                ->first();
        } else {
            $cart = self::sessionCookie(Cart::getCartCookie())
                    ->active()
                    ->noChecked()
                    ->orderBy('id', 'desc')
                    ->first();
            //dd($cart);
        }
        $cartId = empty($cart) ? null : $cart->id;
        \Session::put('cart_id', $cartId);
        \Session::save();
        if (isset($cart->cartItems)) {
            foreach ($cart->cartItems as $item) {
                if ($item->active && is_null($item->item)) {
                    $item->deactivate();
                    $item->save();
                }
            }
        }
        return $cart;
    }
    
    
    
    
    
    
}
