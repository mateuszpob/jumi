<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller{

    /*
     * Wyswietla widok koszyka
     */
    public function index(){
        $cart = \App\Cart::getCurrentCart();
        if(is_object($cart)){
            
            $cart_items = $cart->getItemInCartNameList();
            if($cart_items)
                return view('shop.mimity.cart')
                ->withCart($cart)
                ->withItems($cart_items)
                ->withSchema_id('cart') // do podswietlenia opcji start w menu
                ->withTotal_amount($cart->getTotalAmount());
            
            $referrer = redirect()->getUrlGenerator()->previous();
            if($referrer != url('cart'))
                return \Redirect::to($referrer);
            return \Redirect::to(null);
        }
        
        return \Redirect::to(redirect()->getUrlGenerator()->previous());
        
    }
    /*
     * Pobiera Liste itemow (nazwa, liczba, cena pojedynczego),
     * Potem to leci do carta w menu
     */
    public function getCartItemsList(){
        $cart = \App\Cart::getCurrentCart();
        if(is_object($cart)){
            $items_in_cart = $cart->getItemInCartNameList();
            $total_amount = $cart->getTotalAmount();
            $shipment_amount = $cart->calculateShipmentPrice();

            return array('success' => 1, 'items' => $items_in_cart, 'total_amount' => $total_amount, 'shipment_amount' => $shipment_amount);
        }else{
            return array('success' => 0);
        }
    }
    
    /*
     * Pobiera calkowita liczbe itemow w koszyku i ich calkowita wartosc piniendzy
     * Potem to JS leci do carta w menu
     */
    public function refreshCart(){
        $last_cart = \App\Cart::getCurrentCart();
        if(is_object($last_cart)){
            $shipment_amount = $last_cart->calculateShipmentPrice();
            return array('success' => 1, 
                'total_quantity' => $last_cart->getCartQuantityTotal(), 
                'total_amount' => $last_cart->getTotalAmount(),
                'shipment_amount' => $shipment_amount
            );
        }else{
            return array('success' => 0);
        }
        
    }
    
    /*
     * Dodawanie itemow do koszyka, zalogowani i niezalogowani: decyduje -> shop.buy_not_logged
     */
    public function addToCart(Request $request){//dd($request->variant_id);
    //
   
        $cart = \App\Cart::getCurrentCart();
        
        if (empty($cart)) {
            $cart = new \App\Cart();
            
            if (\Auth::check()) {
                $cart->user_id = \Auth::id();
                $cart->cookie_session_id = null;
            } else {
                $cart->user_id = null;
                $cart->cookie_session_id = \App\Cart::getCartCookie();
            }
            $cart->save();
            
            \Session::set('cart_id', $cart->id);
            \Session::save();
        }
        
        // get item id who be added to cart
        $item_id = $request->item_id;
        // variant itemu (opcja)
        $variant_id = $request->variant_id;


        // Add item to cart, if exist - update quantity
        if($item_id > 0){
            if($variant_id){
                $cart_add_res = $cart->addItemToCart($item_id, $variant_id);
            }else{
                $cart_add_res = $cart->addItemToCart($item_id, $variant_id);
            }

            

            if(isset($cart_add_res['has_variants']) && $cart_add_res['has_variants']){
                // masz warianty to spierdalaj, wybierz jaki czy coś
                return array('success' => 0);
            }else{
                if($cart_add_res['quantity'] >= 0){
                    return array('success' => 1, 'result' => $cart_add_res['quantity']);
                }else{
                    return array('success' => 0);
                }
            }
        }
        return array('success' => 0);
    }
    /*
     * Zwieksz liczbe danego przedmiotu w koszyku o 1.
     */
    public function quantityUp(Request $request){
        $cart_item = \App\CartItem::find($request->item_id);
        $actual_quantity = $cart_item->quantity;
        $cart_item->quantity = $actual_quantity+1;
        $cart_item->save();
        if($cart_item->price_discounted)
            $price = $cart_item->price_discounted;
        else
            $price = $cart_item->price;
        return array('success' => 1, 'quantity' => $cart_item->quantity, 'price' => number_format($price, 2, '.', ' '));
    }
    /*
     * Zmniejsz liczbe danego przedmiotu w koszyku o 1.
     */
    public function quantityDown(Request $request){
        $cart_item = \App\CartItem::find($request->item_id);
        $actual_quantity = $cart_item->quantity;
        $cart_item->quantity = $actual_quantity-1;
        if($cart_item->quantity >= 1){
            $cart_item->save();
            if($cart_item->price_discounted)
                $price = $cart_item->price_discounted;
            else
                $price = $cart_item->price;
            return array('success' => 1, 'quantity' => $cart_item->quantity, 'price' => number_format($price, 2, '.', ' '));
        }
        // jesli w koszyku tego itemu bylo tylko jeden, nie mozna zmniejszyc do zera
        return array('success' => 0);
    }
    /*
     * Usówa element z koszyka całkowiciez
     */
    public function removeFromCart(Request $request){
        \App\CartItem::find($request->item_id)->delete();
        if(\App\CartItem::find($request->item_id) == null)
            return array('success' => 1);
        else
            return array('success' => 0);
    }
    /*
     * Dopisuje shipment_id do sesji i przekierowuje na stronę z Checkoutem 
     * (z formularzem z danymi do ordera)
     */
    public function cartCheckout(Request $request){
        $cart = \App\Cart::getCurrentCart();
        if(is_object($cart)){
            \Session::put('shipment_id', (int)$request->get('shipment_id'));
            return \Redirect::to('/checkout');
        }
        
        return \Redirect::to(null);
        
        
    }
    
}
