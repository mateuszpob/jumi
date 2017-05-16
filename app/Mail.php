<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $table = 'mail';
    
    protected static $in_create_form = [
        'name' => 'text',
        'content' => 'textarea',
        'active' => 'checkbox',
    ]; 
    
    protected $in_edit_form = [
        'id' => ['text','disabled'],
        'name' => ['text',''],
        'content' => ['textarea',''],
        'active' => ['checkbox',''],
    ];
    
    // this is for add user template to generate create form
    public static function getFillableCreate(){
        return self::$in_create_form;
    }
    
    public function getFillableEdit(){
        return $this->in_edit_form;
    }
    
    public function scopeActive($query){
        return $query->where('active', true);
    }
    
    
    
    
    
    
    // ====================================================================== //
    /*
     * name - nazwa maila w bazie
     * address - email na ktory wysylam maila
     * args - tablica asoc. klucz=>wartosc zmiennych w mailu
     * address_name - nazwa odbiorcy maila
     */
    public static function sendByName($name, $address, $args, $address_name = null){
        $mail = \App\Mail::where('name', $name)->first();

        if(\View::exists('emails.'.$mail->name)){
            
        }
        
        $html = $mail->content;
        foreach($args as $key=>$val){
            $html = str_replace('{{$'.$key.'}}', $val, $html);
        }
        
        $address_data = array('mail' => $address, 'name' => $address_name);
        \Mail::raw($html, function ($m) use ($address_data) {
            $m->from(config('shop.mail_from'), config('shop.mail_from_name'));

            $m->to($address_data['mail'], $address_data['name'] ? $address_data['name'] : null)->subject('Your Reminder!');
        });
    }
    
    
}
