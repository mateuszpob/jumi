<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
    
    use SoftDeletes;
    use \App\Traits\GridTrait;
    
    protected $table = 'categories';
    protected $fillable = ['id_upper', 'name', 'description', 'schema_id'];
    protected $searchable = [
        'id' => 'int',
        'id_upper' => 'int',
        'name' => 'string',
        'description' => 'string',
     
    ];
    protected static $in_create_form = [
        'name' => 'text',
        'description' => 'textarea',
        'active' => 'checkbox',
        'on_main_page' => 'checkbox',
    ]; 
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'description' => ['textarea',''],
        'active' => ['checkbox',''],
        'on_main_page' => ['checkbox',''],
    ];
    
    public function items() {
        return $this->belongsToMany('App\Item', 'category_item');
    }
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    public function getFillableEdit(){
        return $this->in_edit_form;
    }
    
    /*
     * If '$to_form' is true, return 
     * with 'No parrent category' first option.
     * 
     * Without '$this_id' category id.
     */
    public static function getAllCategoryNamesWithIdes($to_form = false, $this_id = 0){
        // admin widzie wszystko
        if(\Auth::check() && \Auth::user()->isAdmin()){
            $data = self::query()->orderBy('name')->get();
        }else{
            // user tylko swoje
            $data = self::whereIn('schema_id', \Auth::user()->getSchemas()->orderBy('name')->get()->pluck('id')->all())->orderBy('name')->get();
        }
        
        $out = array();
        if($to_form)
            $out[0] = 'No category';
        foreach($data as $c){
            if($c->id == $this_id)
                continue;
            $out[$c->id] = $c->name;
        }
      
        return $out;
    }
    /*
     * Zwraca Itemy ze wszystkic kategorii dla ktorych ta jest parentem
     */
//    public function getChildrenItems(){
//        $ides = \App\Category::select('id')->where('id_upper', $this->id)->get()->all();
//        
//        return \App\Item::query()->leftJoin('category_item', 'items.id', '=', 'category_item.item_id')
//                ->leftJoin('categories', 'categories.id', '=', 'category_item.category_id')
//                ->where('categories.id_upper', $this->id);
//        
//        
//        $sql = "SELECT i.* FROM items i "
//                . " LEFT JOIN category_item c ON c.item_id = i.id"
//                . " WHERE ci.category_id = ?"
//                . " AND i.active IS true"
//                . " AND c.active IS true";
//    }
    
    /*
     * Kategoria może mieć przypisane itemy tylko wtedy kiedy nie ma żadnych podkategorii.
     * Jeśli ma podkategorie, należy przypisać itemy do którejś podkategorii.
     * Zwraca true jeśli kategoria ma podkategorie (czyli nie może mieć przypisanych itemów)
     * W przeciwnym wypadku zwraca false (czyli może mieć przypisane itemy)
     */
    public function hasChild(){
        
    }

    public function getItemsCount(){
        return $this->items()->count();
    }
    
    public function getAllItems(){
        return $this->items();
    }
    

    /*
     * Zwraca obiekt parenta.
     */
    public function getUpper(){
        return \App\Category::find($this->id_upper);
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
    /*
     * zwraca kolekcje kategorii ktorych ta jest parentem
     */
    public function getChildren(){
        return \App\Category::where('categories.id_upper', $this->id)->get();
    }
//    public function getChildrenItems(){
//        $ch = $this->getChildren();
//        if(!$ch->isEmpty()){
//            
//        }
//    }
    /*
     * Zwraca link do siebie
     */
    public function getUrl(){
        return url('kategorie/'.config('shop.shop_link_prefix') . '/' . $this->url_name);
    }
    
    public function getSchemaCategory($c = null){
        return \App\SchemaCategories::find($this->schema_id);
    
    }

    public static function allWithActiveItems(){
        // return \App\Category::where('schema_id', \App\SchemaCategories::where('name', 'Łazienki')->first()->id)->noUpper()->hasItems()->get();


        $f = new \App\Functions;
        $f::loadMenuData();
        $category_ides = [];
        foreach($f::$menu_data as $c){
            if($c['parent']===null)
                $category_ides[] = $c['id'];
        }
        return ['categories' => \App\Category::whereIn('id', $category_ides)->get(), 'ides' => $category_ides];

        dd($categories);


        $sql = "SELECT c.*
                FROM categories c 
                LEFT JOIN category_item ci ON c.id = ci.category_id 
                GROUP BY c.id 
                HAVING count(ci.item_id) > 0 
                OR c.id IN (SELECT c.id_upper AS co 
                            FROM categories c 
                            LEFT JOIN category_item ci ON c.id = ci.category_id 
                            GROUP BY c.id 
                            HAVING count(ci.item_id) > 0)";

                // "AND c.id IN (SELECT ci2.category_id FROM category_item ci2 LEFT JOIN items i2 ON i2.id = ci2.item_id WHERE i2.active IS true )";
        $categories = \DB::select($sql);
        return $categories;
    }








    
    public function scopeNoUpper($query){
        return $query->where('categories.id_upper', null);
    }
    /*
     * @TODO zrobic skołpa (kategoria musi miec itemy)
     */
    public function scopeHasItems($query){return $query;
        return $query->leftJoin('category_item', 'category_item.category_id', '=', 'categories.id')->havingRaw('count("category_item")>0')
        ->groupBy('categories.id')
        ->groupBy('category_id.id')
        // ->groupBy('categories.id')
        // ->groupBy('categories.id')
        ->groupBy('categories.id');
    }
    
    public function scopeHaveUpper($query){
        return $query->where('categories.id_upper', '!=', null);
    }
    
    public function scopeNoChild($query){
        
    }
    
    //****************************//
    
    
    
    
}
