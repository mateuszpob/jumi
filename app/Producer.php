<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producer extends Model
{
    use SoftDeletes;
    use \App\Traits\GridTrait;
    
    protected $table = 'producer';
    
    protected $fillable = ['name', 'image_path', ];
    
    protected $searchable = [
        'id' => 'int',
        'name' => 'string',
        
    ];
    protected static $in_create_form = [
        'name' => 'text',
    ]; 
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
    ];
    
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    
    public function getFillableEdit(){
        return $this->in_edit_form;
    }
    
    public function items(){
        return $this->hasMany('App\Item');
    }
    
    public function scopeActive($query){
        return $query->where('active', true);
    }
    /*
     * Zwraca kolekcje kategorii w ktorych sa produkty tego producenta
     */
    public function getCategories(){
//        $items = \App\Item::where('producer_id', $this->id)->get();
//        $items_collection = [];
//        foreach($items as $i){
//            $items_collection = array_merge($items_collection, $i->categories()->get()->all());  
//          
//       }
        $sql = "SELECT DISTINCT c.name FROM items i "
                . "LEFT JOIN category_item ci ON ci.item_id = i.id "
                . "LEFT JOIN categories c ON c.id = ci.category_id "
                . "WHERE i.producer_id = ?";
        $categories = \DB::select($sql, [$this->id]);
        return $categories;
    }
    /*
     * Zwraca link do siebie
     */
    public function getUrl(){
        return url('producenci/' . str_replace(' ', '-', strtolower($this->name)));
    }
    /*
     * Pobiera src obrazka tej kategorii
     */
    public function getImageSrc(){//dd($this->items()->where('image_path', '!=', null)->get());
        if($this->image_path)
            return $this->image_path;
        elseif( $this->items()->first())
            return $this->items()->first()->image_path;
        
    }
//    public function items() {
//        return $this->belongsToMany('App\Item', 'category_item');
//    }
}
