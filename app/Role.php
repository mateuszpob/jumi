<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \App\Traits\GridTrait;

class Role extends Model {
    
    use SoftDeletes;
    use GridTrait;
    
    protected $table = 'roles';
    protected $fillable = [
        'name' => 'string',
    ];
    protected $searchable = [
        'name' => 'string',
        'id' => 'int'
    ];
    
    public function users() {
        return $this->belongsToMany('App\User', 'role_user');
    }
    
    public function definitions() {
        return $this->hasMany('App\RoleDefinition');
    }
    
}