<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Route;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function __construct(){

       // $controllerPath =  Route::getCurrentRoute()->getPath();

        $routeArray = app('request')->route()->getAction();

        $controllerAction = class_basename($routeArray['controller']);

       list($controller, $action) = explode('@', $controllerAction);

        $params = ['ProfileController',
                    'NotesController',
                    'AlbumsController',
                    'VideosController',
                    'NewsFeedController',
                    'FollowController',
                    'NotificationsController'];

        if(in_array($controller, $params))
        {
            $visible = true;

        } else {

            $visible = false;
        }

        view()->composer('layouts.frontend.app', function($view) use ($visible)
        {
            $view->with('enable_profile_menu', $visible);
        });

    }
}
