<?php

namespace App\Http\Middleware;

use Closure;

class AnalyticsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '89.65.87.218')
            return $next($request);
        
        $line_to_write = '[' . \Carbon\Carbon::now()->toDateTimeString() . '] '; 
        $line_to_write .= '[' . $_SERVER['REMOTE_ADDR'] . '] ' ;       
        $line_to_write .= '[' . $_SERVER['REQUEST_METHOD'] . '] --> ' ;       
        $line_to_write .= $_SERVER['HTTP_USER_AGENT'];       
        $line_to_write .= PHP_EOL;
        $line_to_write .= '                     '.$_SERVER['REQUEST_URI'];
        $line_to_write .= PHP_EOL;
        
        $myfile = fopen(public_path("analytics.txt"), "a");
        fwrite($myfile, $line_to_write);
        fclose($myfile);
        
        return $next($request);
    }
}