<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleDefinition extends Model {
    
    public $timestamps = false;
    protected $fillable = [
        'action',
        'role_id'
    ];
    
    public function role() {
        return $this->belongsTo('App\Role');
    }

}
