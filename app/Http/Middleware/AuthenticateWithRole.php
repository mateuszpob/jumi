<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateWithRole {

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        

        $action = $request->route()->getActionName();
        $user = $request->user();
     
        if(!$user) return $this->handle_not_logged($request);
//        \Debugbar::addMessage($user->roles()->get(array('id'))->toArray());
        $my_roles=$user->roles->lists('id');

        $access = \DB::table('role_definitions')//->selectRaw('COUNT(*)')
                ->whereIn('roles.id', $my_roles)
                ->where(function ($query) use ($action){
                    $query->where('role_definitions.action',$action)
                    ->orWhere('role_definitions.action',"*");
                })
                ->join('roles','role_definitions.role_id','=','roles.id')
                ->whereNull('deleted_at')
                ;
            //dd($this->handle_not_logged($request)) ;
        if($access->count()>0){
//            dd($request);
            return $next($request);
        }else{
            return $this->handle_not_logged($request);
        }
        //dd($access);
        //go to route if everything is ok
        
    }

    /**
     * Handles not allowed user depending on request type
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function handle_not_logged(\Illuminate\Http\Request $request) {
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('auth/login');
        }
    }

}
