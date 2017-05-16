<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
    /*
     * To zwraca Itema powiazanego z tym OrderItemem
     */
    public function item(){
        return $this->hasOne('App\Item', 'id', 'item_id');
    }

    public function order(){
        return $this->belongsTo('App\Order');
    }
    public function variant(){
        return $this->hasOne('App\ProductVariant', 'id', 'variant_id');
    }

}
