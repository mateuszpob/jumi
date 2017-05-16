<?php namespace App\Http\Controllers;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use Validator;
use Response;

class RoleController extends Controller{
    
    use \App\Traits\GridTrait;
    
    public function index(){
        
        if (\Request::ajax()){
            return $this->gridAction('\App\Role');
        }else{
            return view('admin.role.index');
        }
    }
        
    public function create(){
        return view('admin.role.add');
    }
    
    public function edit(Router $router,$id){
        
        
        $list = $router->getRoutes();
        
        $data=array();
        foreach($list as $route){
            
            $m=$route->middleware();
            
            if(empty($m)  || !in_array('role', $m)){
                continue;
            }
           // echo '<pre>' . print_r($m, 1) . '</pre>';
            
            
            $item = str_pad(implode('+', $route->methods()),12,' ');
            $item.= " | ".str_pad($route->uri(), 40,' ');
            $item.= " | ".str_pad($route->getName(),25,' ');
            $item.= " | ".str_replace('App\Http\Controllers\\', '', str_pad($route->getActionName(),61));
            $data[$route->getActionName()]=  str_replace(' ', '&nbsp;', $item);
        }
        //\DebugBar::addMessage($data);
        
//        GET+HEAD                 | admin                                   .
//        POST                     | admin/users                             
                
        return view('admin.role.edit')->withRole(\App\Role::find($id))->withRoutes($data);
    }
        
    public function destroy($id){
        return \App\Role::destroy($id);
    }
    
    public function store(\App\Http\Requests\NewRoleRequest $request){
        if($request->has('id')){
            $r = \App\Role::find($request->id);
        }else{
            $r = new \App\Role();
        }
        $r->name = $request->role_name;
        $r->definitions()->forceDelete();
        $r->save();
//        dd($request->roles);
        $roles=$request->roles;
        if(!empty($roles)){
            
            foreach($roles as $action){
                $definition= new \App\RoleDefinition;
                $definition->action = $action;
                $r->definitions()->save($definition);
            }
            $r->save();
        }
        
        //return \Redirect::route('admin.roles.index');
        return \Redirect::action('RoleController@index');
        //return Response::make('Role added.');
        
        
        
        
        
        
        
        
        /*
        $v = Validator::make($request->all(), [
            'role_name' => 'required|string'
        ], $this->validation_messages);
        
        if ($v->fails()){
            return redirect()->back()->withErrors($v->errors());
        }else{
            //$r = new Roles();
//            $r->name = 
        }
        //return App\Roles::all();
        */
        
    }
}