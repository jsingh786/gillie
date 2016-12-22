<?php

namespace App\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Log;

class LaravelLoggerProxy {
    public function log( $msg ) {
        Log::info($msg);
    }
}


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
//        $pusher = $this->app->make('pusher');
//        $pusher->set_logger( new LaravelLoggerProxy() );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        /*$this->app()->bind('elastic',function(){
            return (new ClientBuilder());
        });*/
        
    }
}
