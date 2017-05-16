<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
//    use SoftDeletes;
    
    public function scopeUrl($query, $url){
        return $query->where('pages.url', $url);
    }
    public function scopeActive($query){
        return $query;
    }
    
}
