<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class NewRoleRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
                    'role_name' => 'required|string',
		];
	}
        
        public function messages(){
            return [
                'role_name.required'    => 'The role name field is required.',
                'role_name.string'      => 'The role name field must be text.',
            ];
        }
        
        
        

}
