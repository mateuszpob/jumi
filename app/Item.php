<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    
    protected $fillable = ['name', 'description', 'price', 'price_producer', 'count', 'active', 'weight', 'image_path', 'code', 'category_name', 'schema_id'];
    protected $table = 'items';
    protected $searchable = [
        'id' => 'int',
        'name' => 'string',
        'description' => 'text',
        'price' => 'float',
        'category' => 'text',
        'category_name' => 'text',
        'code' => 'text',
        'schema_id' => 'text',
        'price_producer' => 'float'
    ];
    protected static $in_create_form = [
        'name' => 'text',
        'description' => 'textarea',
        'price' => 'text',
        'price_producer' => 'text',
        'ean' => 'text',
//        'category_name' => 'text',
//        'code' => 'text',
//        'count' => 'text',
//        'weight' => 'text',
        'active' => 'checkbox',
    ];    
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'description' => ['textarea',''],
        'price' => ['text',''],
        'price_producer' => ['text',''],
        'ean' => ['text',''],
//        'category_name' => ['text',''],
//        'code' => ['text',''],
//        'count' => ['text','1'],
//        'weight' => ['text',''],
        'active' => ['checkbox',''],
    ];
    

    public function schema() {
        return $this->belongsTo('App\SchemaCategories');
    }
    public function shipment() {
        return $this->belongsTo('App\Shipment');
    }
    public function producer() {
        return $this->belongsTo('App\Producer');
    }
    
    public function categories() {
        return $this->belongsToMany('App\Category', 'category_item');
    }
    
    public function productGroups() {
        return $this->belongsToMany('App\ProductGroup', 'product_group_items');
    }

    public function variants(){
        return $this->hasMany('App\ProductVariant');
    }
    // zwraca nazwe schematu (taką do wyświetlenia na str) w ktorym jest ten item
    // public function getSchemaName(){
    //     if($schema = $this->schema()->first())
    //         return $schema->name;
    //     else 
    //         return null;
    // }
    // zwraca tablice kategori w jakich jest item
    public function getCategoryList(){
        $origin_category = $this->categories->first();
        if(!$origin_category){
            return null;
        }
        $tmp = $origin_category;
        $list = [$tmp];
        while($up = $tmp->getUpper()){
            $list[] = $up;
            $tmp = $up;
        }
        return array_reverse($list);
    }

    public function getCategoryId(){
        
    }
    public function getAltText(){
        $d = explode(' ', $this->description);
        switch(count($d)){
            case 1:
                return $d[0];
                break;
            case 2:
                return $d[0].' '.$d[1];
                break;
            default:
                return $d[0].' '.$d[1].' '.$d[2];
        }
    }

    // this is for add user template to generate create form
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    
    public function getFillableEdit(){
        return $this->in_edit_form;
    }
    
    public function getCategoriesIds(){
        $categories_ids = array();
        foreach($this->categories->all() as $c){
            $categories_ids[] = $c->id;
        }
        
        return $categories_ids;
    }

    public function getPrice(){
        $variant = $this->variants()->orderBy('price')->first();
        if($variant)
            return $variant->price;
        return $this->price;
    }
    public function getPriceDiscounted(){
        $variant = $this->variants()->orderBy('price')->first();
        if($variant)
            return $variant->price_discounted;
        return $this->price_discounted;
    }
    public function getdiscountPercentage(){
        return ((100*$this->price_discounted/$this->price)-100)*(-1);
    }
    public function getUrl(){
        return url(config('shop.item_page_prefix').'/'.$this->id.'/'.\App\Functions::createUrlFromString($this->name));
    }

    public function scopeActive($query){
        return $query->where('items.active', true)
                ->whereNotNull('schema_id');
                    //->where('price', '>', 0); // bo itemy wariantowane maja price = 0, to sie nie nada
    }
    /*
     * Czy item ma powiazane itemy (na stronie produktu uzywane narazie)
     */
    public function hasRelatedItems(){
        return true;
    }
    /*
     * Zwroc obiekty itemow powiązanych.
     * -> powiązane czyli narazie z tej samej kategorii
     */
    public function getRelatedItems($take = 0){
        // $count = $this->categories()
        //         ->first()
        //         ->items()
        //         ->active()
        //         ->where('id', '<>', $this->id)->get()->count();
        // if($take > $count)
        //     $take = $count; 

        // return $this->categories()
        //         ->first()
        //         ->items() 
        //         ->active()
        //         ->where('id', '<>', $this->id)->get()->random($take)->all();
//===============
        // $items = \App\Item::query()->leftJoin('category_item', 'category_item.item_id', '=', 'items.id')
        //                     ->where('category_item.category_id', function($query){
        //                         $query->select('category_item.category_id')
        //                             ->from(with(new \App\CategoryItem)->getTable())
        //                             ->where('category_item.item_id', $this->id);
        //                     })->active()->where('items.id', '<>', $this->id)->orderByRaw('RANDOM()')->take($take)->get();
        // return collect($items);
        $item_collection = collect();
        foreach($this->categories as $c){
            $items = $c->items()->active()->where('id', '<>', $this->id)->get();
            $item_collection = $item_collection->merge($items);
        }
        $unicue_items = $item_collection->unique();
        $unicue_items_count = $unicue_items->count();
        if($unicue_items_count < $take)
            $take = $unicue_items_count; 
        if($take > 0)
            if($take==1){
                $coll = new \Illuminate\Database\Eloquent\Collection();
                return $coll->add($unicue_items->random($take));
            }
            else
                return $unicue_items->random($take);    
        return $unicue_items->unique();

    }
    
    
    public static function getRandomItems($take = 1){
        return \App\Item::active()->get()->random($take);
    }

    /*
     * Zwraca seo description strony produktu
     */
    public function getSeoPageDescription(){
        return strip_tags(substr($this->description, 0, 160));
    }
    /*
     * Zwraca seo description strony produktu
     */
    public function getSeoPageTitle(){
        return $this->name . ' | Artykuły sanitarne - ' . ucfirst($this->categories->last()->name);
    }
    
    
    
}
