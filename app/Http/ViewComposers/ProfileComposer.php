<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/16/2016
 * Time: 3:16 PM
 */

namespace App\Http\ViewComposers;
use Auth;
use Illuminate\View\View;

class ProfileComposer
{

    /**
     * Create a profile composer.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user_repo = new \App\Repository\usersRepo;
        $user_id = Auth::Id();
        $view->with('latestMovie', end($this->movieList));
    }
}