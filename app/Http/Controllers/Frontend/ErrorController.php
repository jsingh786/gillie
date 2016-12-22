<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 11/18/2016
 * Time: 7:10 PM
 */


/**
 * Created by PhpStorm.
 * User: rawatabhishek
 * Date: 11/4/2016
 * Time: 9:38 AM
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


Class ErrorController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function pageNotFound(){

       return view('frontend.error.page-not-found');
    }
}