<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public $timestamps = false;
    protected $table = 'shipments';
    protected $fillable = ['name', 'description', 'price', 'max_quantity', 'active', 'quantity_multiplication'];
    protected $searchable = [
        'id' => 'int',
        'name' => 'string',
        'description' => 'text',
        'price' => 'float',
    ];
    protected static $in_create_form = [
        'name' => 'text',
        'price' => 'text',
        'max_quantity' => 'text',
        'price_percentage' => 'text',
        'description' => 'textarea',
        'quantity_multiplication' => 'checkbox',
        'active' => 'checkbox',
    ]; 
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'price' => ['text',''],
        'price_percentage' => ['text',''],
        'max_quantity' => ['text',''],
        'description' => ['textarea',''],
        'quantity_multiplication' => ['checkbox',''],
        'active' => ['checkbox',''],
    ];
    
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    
    public function getFillableEdit(){
        return $this->in_edit_form;
    }
    
    
    public function scopeActive($query){
        return $query->where('active', true);
    }
    
}
