<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller {
    
    use \App\Traits\GridTrait;
    
    /*
     * Wyświetla stronę checkout (button kupuje z koszyka)
     * Redirect from CartController@cartCheckout
     */

    public function displayCheckoutPage(Request $r, $register = null) {
        // skąd przyszedłem
        $referer = trim(str_replace(url(), '', \Request::header('referer')), '/');
     
        // jesli niema koszyka
        if(!\Session::has('cart_id') || null == \App\Cart::where('id', \Session::get('cart_id'))->noChecked()->active()->first()){
            return \Redirect::to(null);
        }
        // user zalogowany, przekirowujemy do formularza
        // checkouta wypelnionego danymi zalogowanego usera
        if(\Auth::check()){
            $address_data = array(
                'first_name' => \Auth::user()->first_name ,
                'last_name' => \Auth::user()->last_name ,
                'email' => \Auth::user()->email ,
                'telephone' => \Auth::user()->telephone ,
                'address' => \Auth::user()->address ,
                'city' => \Auth::user()->city ,
                'postcode' => \Auth::user()->postcode ,
            );
            return view('shop.mimity.checkout')->withAddress_data($address_data);
        }
        // jesli user wybral opcje z rejestracją
        if($register === 'register')
            return view('shop.mimity.checkout_with_register');
        // jesli przyszedlem z checkout to mnie nei rpzekierowuj juz 
        // do pytania o rejestracje tylko juz do forma checkoutu
        if($referer === 'checkout')
            return view('shop.mimity.checkout');
        
        return view('shop.mimity.before_checkout');
    }

    /*
     * Wyswietla stroe na ktorej wybierasz platnosc i wprowadzasz dane do zamowienia
     */
    public function checkoutOrder(Request $request) {

        $validator = \Validator::make($request->all(), [
                    'first_name' => 'required|max:32',
                    'last_name' => 'required|max:32',
                    'email' => 'required|email',
                    'telephone' => 'required|max:32',
                    'address' => 'required|max:128',
                    'city' => 'required|max:32',
                    'postcode' => 'required|max:6',
        ]);

        $request->flash();
        // Wróć do formulaża i pokaż błędy, jeśli są błedy
        if ($validator->fails()) {
            return redirect('/checkout')
                            ->withErrors($validator)
                            ->withInput();
        }

        // W formularzu wszystkie pola wypełnine prawidłowo,
        // Laduje koszyk do potwierdzenia,
        // Przekerowujemy do strony z potwierdzeniem zamowienia
        $cart_id = \Session::get('cart_id');
        if ($cart_id > 0) {
            $cart = \App\Cart::find($cart_id);
            $cart_items = $cart->getItemInCartNameList();
            // oblicz cene wysyłki 
            $shipment_amount = $cart->calculateShipmentPrice();
            
            return view('shop.mimity.confirm_order')
                            ->withData($request->all())
                            ->withItems($cart_items)
                            ->withShipment_amount($shipment_amount)
                            ->withTotal_amount($cart->getTotalAmount() + $shipment_amount);
        }
    }
    
    public function checkoutOrderWithRegister(Request $request){
        $c = new \App\Http\Controllers\Auth\AuthController();
        $c->postRegister($request);
        $this->checkoutOrder($request);
    }

    public function createOrder(Request $request) {
        
        $cart = null;
        if(\Session::has('cart_id')){
                $cart = \App\Cart::where('id', \Session::get('cart_id'))
                        ->noChecked()
                        ->active()
                        ->orderBy('created_at', 'desc')
                        ->first();
        }    
        if ($cart && $cart->getCartQuantity() > 0) {
            $payment_type_id = (int)$request->get('payment_type_id');
            // Tworze nowego ordera
            $new_order = new \App\Order();

            $new_order->first_name = $request->get('first_name');
            $new_order->last_name = $request->get('last_name');
            $new_order->email = $request->get('email');
            $new_order->telephone = $request->get('telephone');
            $new_order->address = $request->get('address');
            $new_order->city = $request->get('city');
            $new_order->postcode = $request->get('postcode');
            $new_order->payment_type_id = $payment_type_id;
            if ($request->get('comment')) {
                $new_order->comment = $request->get('comment');
            }
            $new_order->weight_total = $cart->getTotalWeight();

//            $shipment = \App\Shipment::findOrFail(\Session::get('shipment_id'));
//            $new_order->shipment_id = 0;//$shipment->id;

            // przypisuje cart_id do ordera 
            $new_order->cart_id = $cart->id;

            $cart_amount = $cart->getTotalAmount();
            $new_order->cart_amount = $cart_amount;
            
            $shipment_amount = $cart->calculateShipmentPrice();
            $new_order->shipment_amount = $shipment_amount;
            $new_order->total_amount = $cart_amount + $shipment_amount;
            // hash ktory bedzie w linku do potwierdzenia ordera
            $new_order->confirm_hash = md5(time() + 'w3dupy');
            $new_order->save();

            // pobiera cart_itemy
            $cart_items = $cart->getCartItems();

            foreach ($cart_items as $c_item) {
                $new_order->addItemToOrder($c_item);
            }

            // jak juz sie zrobily order_itemy, to ustawiam active na false w orderze,
            // tylko z active false (zakonczony pomyslnie) order bedzie dalej rozpatrywany,
            $new_order->active = false;
            $new_order->save();

            

            // Wybierz PŁATNOŚĆ
            switch($payment_type_id){
                case 2: // stwórz zamówienie PayU
                    $redirect_url = \App\Payu::createOrder($new_order);
                    return \Redirect::to($redirect_url);
                    break;
            }



            // przejsc do widoku z wiadomoscia po zamowieniu
            return view('shop.mimity.order_final')->withOrder_id($new_order->id)->withTotal_price($new_order->cart_amount + $new_order->shipment_amount);
        } else {
            \Log::notice('Zjebał się koszyk. W sesji nie bylo id, albo byl pusty, bez itemow.');
            return \Redirect::to(null);
        }
    }
    /*
     * Tutaj wraca user po dokonaniu płatnośći
     */
    public function paymentReturn($order_id){
        $order = \App\Order::find($order_id);
        if($order)
            return view('shop.mimity.payment_return')->withOrder($order);
        abort(404);
    }

    // Potwierdz zamowienie, tu trafia klient ktory kliknie 
    // w link w mailu potwierdzającym. 
    // Tu wysyla sie mail do sprzedajacego, ze jest nowe zamowienie, 
    // w mailu są linki z akcjami
    public function confirmFromMail($id, $hash) {
        $order_to_confirm = \App\Order::FindOrFail((int) $id);
        // potwierdzenie zamowienia
        if ($order_to_confirm->confirm_hash == $hash) {
            $order_to_confirm->confirm_date = new \DateTime('now');
            $order_to_confirm->save();
            
            // wyślij maila z wiadomoscia i akcjami do sprzedajacego 
            $args = array(
                'first_name' => $order_to_confirm->first_name,
                'last_name' => $order_to_confirm->last_name,
                'address' => $order_to_confirm->address,
                'city' => $order_to_confirm->city,
                'postcode' => $order_to_confirm->postcode,
                'telephone' => $order_to_confirm->telephone,
                'comment' => $order_to_confirm->comment,
                'sent_url' => url('order-sent/' . $order_to_confirm->id . '/' . $order_to_confirm->confirm_hash),
//                'items_html' => view('')

            );
            \App\Mail::sendByName('order_confirmed', config('shop.order_confirmed_mail'), $args);
            return view('shop.mimity.order_confirmed');
        }
    }
    
    public function markOrderAsSent($id, $hash){
        $order = \App\Order::where('id', $id)->where('confirm_hash', $hash)->first();
        // jesli mozna za pobraniem Lub jesli juz oplacone, mozesz wyslac
        if ($order->pay_delivery == true || $order->payd == true) {
            $order->sent_date = new \DateTime('now');
            $order->save();
        
            echo 'Zamowienie nr: ' . $id . ' oznaczone jako wysłane.';
            exit;
        }
        echo 'Error :(';
        exit;
      
    }    
    
    
    
    // =================== ORDERY OD STRONY PANELU ADMINA =================== //
    /*
     * For datatables in admin view.
     */
    private function gridAction(){
            $table_data = \Request::all();
            $count = \App\Order::query()->count();            
            $search = \App\Order::query();
            if(!empty($table_data['search']['value']))
                $search->searchGrid($table_data['search']['value']);
            
            $data = array(
                "draw"            => $table_data['draw'],
                "recordsTotal"    => $count,
                "recordsFiltered" => $search->count(),
                "data"            => null
            );
            //order after getting count
            foreach($table_data['order'] as $order){
                $search->orderBy($table_data['columns'][$order['column']]['data'], $order['dir']);
            }
            $data['data']=$search->take($table_data['length'])->offset($table_data['start'])->get();
            
            return $data;
    }
    /*
     * Pokazuje liste zamowien
     */
    public function getIndex(){
        if (\Request::ajax()){
            return $this->gridAction();
        }else{
            return view('admin.order.index');
        }
    }
    
    public function getShow($id){
        $order = \App\Order::findOrFail($id);
        return view('admin.order.show')
                ->withOrder($order)
                ->withShipment(\App\Shipment::find($order->shipment_id))
                ->withOrder_items($order->getOrderItems());
    }
    
    public function getCreate(){
        echo 'dupa';
    }
}

/*
 * 
 */