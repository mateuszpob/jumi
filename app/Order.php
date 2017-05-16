<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $searchable = [
        'id' => 'int',
        'address' => 'text',
        'first_name' => 'text',
        'price' => 'float',
        'category' => 'text'
    ];


    public function items() {
        return $this->belongsToMany('App\Item', 'order_items');
    }
    public function cart(){
        return $this->belongsTo('\App\Cart');
    }
    public function paymentLogs(){
        return $this->hasMany('App\PaymentLog');
    }
    // public function orderItems() {
    //     return $this->hasMany('App\OrderItem', 'order_items', 'order_id');
    // }

    /*
     * Tworzy OrderItem dal Ordera z CartItem'a
     */
    public function addItemToOrder(\App\CartItem $cart_item){
        $new_order_item = new \App\OrderItem();
        $new_order_item->order_id = $this->id;
        $new_order_item->item_id = $cart_item->item_id;
        $new_order_item->weight = \App\Item::findOrFail($cart_item->item_id)->weight;
        $new_order_item->quantity = $cart_item->quantity;
        $new_order_item->price = $cart_item->price;
        $new_order_item->price_discounted = $cart_item->price_discounted;
        $new_order_item->ean = $cart_item->ean;
        $new_order_item->variant_id = $cart_item->variant_id;
        $new_order_item->save();
    }
    /*
     * Zwraca sumę całkowitych liczb itemów z ordera
     */
    public function getTotalQuantity(){
        return \App\OrderItem::selectRaw('sum(quantity)')->where('order_id', $this->id)->first()->sum;
    }
    /*
     * Zwraca kolekce order itemów zwartych w zamówieniu.
     * Do kazdego mozna sie odniesc order_item->item
     * żeby wyciągnąć itema przypisanego do tego OrderItema
     */
    public function getOrderItems(){
        return \App\OrderItem::where('order_id', $this->id)->get();
    }
    
    /*
     * Oznacz zamowienie jako opłacone, sprawdz czy w calosci.
     * Deaktywuje koszyk.
     * Wysyła maila do klienta
     */
    public function markAsPaid($received_amount){
        // sprawdz czy hajsy się zgadzają
        if($received_amount >= $this->total_amount){
            $this->payment_received_in_full = true;
            $this->payment_received = $received_amount;
            $this->payment_received_date = \Carbon\Carbon::now();
            $this->save();
            // zczekałtuj koszyk
            $this->cart->checkoutCart();
            // wyślij maila z linkiem potwerdzającym do klienta
            $args = array(
                'name' => $new_order->first_name,
                'confirm_url' => url('confirm-order/' . $this->id . '/' . $this->confirm_hash),
                'order_id' => $new_order->id
            );
            \App\Mail::sendByName('confirm_order', $this->email, $args);
        }else{
            $this->payment_received_in_full = false;
            $this->payment_received = $received_amount;
            $this->payment_received_date = \Carbon\Carbon::now();
            $this->save();
        }
        return $this->payment_received_in_full;
    }
    
}
