<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchemaCategories extends Model
{
    protected $table = 'schema_categories';
    protected $fillable = ['name', 'description', 'owner_id', 'active', 'url'];

    protected $searchable = [
        'id' => 'int',
        'name' => 'string',
        'description' => 'text',
        'owner_id' => 'integer',
        'url' => 'string',
    ];
    protected static $in_create_form = [
        'name' => 'text',
        'description' => 'textarea',
//        'owner_id' => 'text',
        'url' => 'string',
        'active' => 'checkbox'
    ];    
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'description' => ['textarea',''],
//        'owner_id' => ['text',''],
        'url' => ['text',''],
        'active' => ['checkbox','']
    ];
    
    public function items(){
        return $this->hasMany('App\Item');
    }

    // this is for add user template to generate create form
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    
    public function getFillableEdit(){
        return $this->in_edit_form;
    }

    public function getUrl(){
        return url('kategorie/'.$this->url);
    }
    public function scopeActive($query){
        return $query->where('schema_categories.active', true);
    }

}
