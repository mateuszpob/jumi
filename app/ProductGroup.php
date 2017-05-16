<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
	// use SoftDeletes;
    use \App\Traits\GridTrait;

    protected $fillable = ['name', 'description', 'promotion_percentage', 'promotion_start_date', 'promotion_end_date', 'active', 'promotion_once', 'image_path'];
    protected $table = 'product_groups';
    protected $searchable = [
        'id' => 'int',
        'name' => 'string',
        'description' => 'text',
        'promotion_percentage' => 'float',
        'promotion_start_date' => 'text',
        'promotion_end_date' => 'text',
        'image_path' => 'text'
    ];
    protected static $in_create_form = [
        'name' => 'text',
        'description' => 'textarea',
        'promotion_percentage' => 'text',
       	'promotion_start_date' => 'text',
       	'promotion_end_date' => 'text',
       	'promotion_once' => 'checkbox',
        'active' => 'checkbox',
    ];    
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'description' => ['textarea',''],
        'promotion_percentage' => ['text',''],
        'promotion_start_date' => ['text',''],
        'promotion_end_date' => ['text',''],
        'promotion_once' => ['checkbox',''],
        'active' => ['checkbox',''],
    ];

    public function items() {
        return $this->belongsToMany('App\Item', 'product_group_items', 'group_id');
    }
    
    public function getUrl(){
        return url(config('shop.promotion_page_prefix').'/'.$this->id.'/'.\App\Functions::createUrlFromString($this->description, 3));
    }

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
