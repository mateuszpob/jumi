<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendlyUrl extends Model
{
    use SoftDeletes;
    use \App\Traits\GridTrait;
    
    
    protected $table = 'friendly_urls';
    protected $fillable = ['url', 'action'];
    protected $searchable = [
        'id' => 'int',
        'url' => 'string',
        'action' => 'string',
    ];
    
    public static function getRoute($url){
        $actions = array();
        $o_url = self::where('url', $url)
                ->take(1)
                ->get();
        foreach($o_url as $o)
            $actions[] = $o->action;
        
        return $actions[0];
    } 
    
    
    
}
