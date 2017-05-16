<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;
    
    protected $table = 'cart_items';
    
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function getPrice(){
    	if($this->price_discounted)
    		return $this->price_discounted;
    	return $this->price;
    }

    public function item(){
        return $this->hasOne('App\Item', 'id', 'item_id');
    }
    public function variant(){
        return $this->hasOne('App\ProductVariant', 'id', 'variant_id');
    }

    public function scopeActive($query){
        return $query->where('active', true);
    }
    public function scopeNoDeleted($query){
        return $query->where('deleted_at', null);
    }
}
