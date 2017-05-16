<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	//$this->app->bind('path.public', function() {
        //  	//return base_path().'/public_html';
 	//	return __DIR__;
        //});
        $this->app->bind(
                'Illuminate\Contracts\Auth\Registrar',
                'App\Services\Registrar'
        );
        
        
        $this->app->bind('AdamWathan\EloquentOAuth\Authenticator','\App\Bindings\Authenticator');
    }
}
