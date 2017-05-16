<?php namespace App\Http\Middleware;

use Closure;
use \Auth;
use \Redirect;
class checkForOauthNicks {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
if (Auth::check() && $request->path() != 'auth/nick-missing' && stripos($request->path(), '_debugbar')===false){
    $user = Auth::user();
    if(substr( $user->nick, 0, 8 ) === 'newuser_'){
        return Redirect::to('auth/nick-missing', 302);
    }else{
        return $next($request);
    }
}else{
    return $next($request);
}

		
	}

}
