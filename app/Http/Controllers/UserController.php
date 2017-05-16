<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

    use \App\Traits\GridTrait;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()//\Request $request)
	{
           
            if (\Request::ajax()){
                return $this->gridAction('\App\User');
            }else{
                return view('admin.user.index');
            }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            return view('admin.user.add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(\Request $request)
	{
            $user_data = \Request::all();
            $user = \App\User::findOrNew(\Input::get('user_id'));
            
            
            if(isset($user_data['nick'])){
                $user->nick = $user_data['nick'];
            }
            if(isset($user_data['email'])){
                $user->email = $user_data['email'];
            }
            if(isset($user_data['roles'])){
                $user->save();
                $user->roles()->detach();
                foreach($user_data['roles'] as $role_id){
                    $user->roles()->attach($role_id);
                }
            }else{
                $user->save();
                // Set standard user role 'User' when not set role
                $user->roles()->detach();
                $user->roles()->attach(\App\Role::where('name', '=', 'User')->first()->id);
            }
            
            
            
            $user->save();
            return \Redirect::route('admin.users.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
            $user = \App\User::find($id);
            return view('admin.user.edit_user', [
                    'user_id' => $id,
                    'nick' => $user->nick,
                    'email' => $user->email,
                    'user' => $user
                ]);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
