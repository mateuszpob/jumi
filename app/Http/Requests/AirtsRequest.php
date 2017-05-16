<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AirtsRequest extends Request {
    
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null) {
        
        \Validator::extend('unique_airts', function($attribute, $value, $parameters) {
            $value = substr($value, 1);
            return !\App\User::query()->where('nick', '=', $value)->exists();
        },'Nick with this name exists.');
        
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'id' => 'sometimes|exists:airts',
            'source' => ['required', 'regex:/^\//'],
            'target' => 'required|url'
        ];
        
        $unique = 'unique:airts,source';
        if(!empty($this->get('id'))){
            $unique.= ','.$this->get('id').'';
        }
        $rules['source'][]='unique_airts';
        $rules['source'][]=$unique;
        return $rules;
    }

    public function messages() {
        return [
            'source.regex' => 'Link must begin with "/".',
            'terget.url' => 'Target must be full url.'
        ];
    }
}
