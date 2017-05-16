<?php

namespace App\Http\Middleware;

use Closure;

class CartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $c = \App\Cart::getCurrentCart();
        
        
        
        
        
        
        
        return $next($request);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        if(\Auth::check()){
            $cart = \App\Cart::where('user_id', \Auth::id())
                    ->noChecked()
                    ->active()
                    ->orderBy('created_at', 'desc')
                    ->first();
            
            if($cart){
                \Session::set('cart_id', $cart->id);
                \Session::save();
            }else{
                \Session::forget('discount_code');
                \Session::forget('cart_id');
                \Session::forget('shipment_id');
                \Session::save();
            }
            
        }else{
            if(\Session::has('cart_id')){
                $cart = \App\Cart::where('id', \Session::get('cart_id'))
                        ->noChecked()
                        ->active()
                        ->orderBy('created_at', 'desc')
                        ->first();
                if($cart){
                    
                }else{
                    \Session::forget('discount_code');
                    \Session::forget('cart_id');
                    \Session::forget('shipment_id');
                    \Session::save();
                }
            }else{
                $cart = \App\Cart::where('cookie_session_id', $session_id)
                        ->noChecked()
                        ->active()
                        ->orderBy('created_at', 'desc')
                        ->first();
                if($cart){
                    \Session::set('cart_id', $last_cart->id);
                
                    
                }
            }
            
        }
        
        return $next($request);
        
        
        
        
        

        
        
        
        
        
        /*
         * Jesli w sesji jest juz cart_id to sprawdzam czy ten kart sie nadaje,
         * Jak się nie nadaje to wywalamy go z sesi (i inne jego rzeczy)
         */
        if(\Session::get('cart_id') > 0){
            $cart = \App\Cart::where('id', \Session::get('cart_id'))
                    ->noChecked()
                    ->active()
                    ->orderBy('created_at', 'desc')
                    ->first();
            
            if($cart === null){
                \Session::forget('discount_code');
                \Session::forget('cart_id');
                \Session::forget('shipment_id');
                \Session::save();
            }
        }
        
        /*
         * W sesji nie ma cart_id.
         */
        if(!\Session::has('cart_id')){
            $session_id = \Session::getId();

            if(\Auth::check()){
                $last_cart = \App\Cart::where('user_id', \Auth::id())->noChecked()->active()->orderBy('created_at', 'desc')->first();
                if($last_cart){
                    \Session::set('cart_id', $last_cart->id);      
                }
            }else{
                $last_cart = \App\Cart::where('cookie_session_id', $session_id)->noChecked()->active()->orderBy('created_at', 'desc')->first();
                if($last_cart){
                    \Session::set('cart_id', $last_cart->id);
                    
                }
            }
//            dd($last_cart);
        }
        
        
        
        
        
        \Debugbar::info('Cart id: '.\Session::get('cart_id'));
        return $next($request);
    }
}
