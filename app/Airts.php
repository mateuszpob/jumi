<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Airts extends Model {

    use \App\Traits\GridTrait;
    
    protected $searchable = [
        'source' => 'string',
        'id' => 'int',
        'target' => 'string'
    ];
    
    public static function getRoute($name){
        $u=User::firstByAttributes(['nick' => $name]);
        if(!empty($u)){
            return '/users/show/'.$u->id;
        }else{
            $r = self::firstByAttributes(['source' => '/'.$name]);
            if(empty($r)){
                return null;
            }
            return $r->target;
        }
        
    }
}