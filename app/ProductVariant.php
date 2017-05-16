<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
	// public function __construct(){
	// 	parent::__construct();
	// 	$this->data = json_decode($this->data);
	// }

    public function item(){
        return $this->belongsTo('App\Item');
    }

    public function scopeActive($query){
        return $query->where('product_variants.active', true);
    }

    public function getPrice(){
        return $this->price;
    }
    public function getPriceDiscounted(){
        return $this->price_discounted;
    }
}
