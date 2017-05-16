<?php 

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
    
    use Authenticatable,
        CanResetPassword,
        SoftDeletes;
    use \App\Traits\GridTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
      protected $table = 'users';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    
    protected $fillable = [
        'email', 
        'password', 
        'first_name', 
        'last_name', 
        'address', 
        'postcode', 
        'city', 
        'telephone',
        'company_name'
    ];
    protected $searchable = [
        'id' => 'int',
        'nick' => 'string',
        'email' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'company_name' => 'string'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    
    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user');
    }
    // czy user jest adminem?
    public function isAdmin(){
        $roles = $this->roles()->get();
        foreach($roles as $role){
            if($role->name == 'admin'){
                $this->is_admin = true;
                return true;
            }
        }
        $this->is_admin = false;
        return false;
    }
    /*
     * Sprawdza czy user morze edytowac itemy na stronie (normalnie, nie w panelu admina)
     */
    public function hasEditPermissions(){
        $roles = $this->roles()->get();
        foreach($roles as $role){
            if($role->name == 'admin' || $role->name == 'lazienki'){
                return true;
            }
        }
        $this->is_admin = false;
        return false;
    }

    // this is for add user template to generate create form
    public function getFillable(){
        return $this->fillable;
    }
    
    public function details() {
        return $this->hasOne('App\UserDetail','user_id','id');
    }

    public function getSchemas(){
        return \App\SchemaCategories::where('owner_id', $this->id);
    }
    /*
     * Zapisze kto, co, skąd wszedł na strone [/storage/app/users_activity.txt]
     */
    public static function registerActivity(){
        // if($_SERVER['REMOTE_ADDR'] != '89.65.87.218'){
        //     $line_to_write = '['.\Carbon\Carbon::now()->toDateTimeString().'] ['
        //     .$_SERVER['REMOTE_ADDR'].'] ['
        //     .$_SERVER['HTTP_REFERER'].'] '
        //     .$_SERVER['HTTP_USER_AGENT'];
        //     \File::append(storage_path('app/users_activity.txt'), $line_to_write.PHP_EOL);
        // }
    }
}
