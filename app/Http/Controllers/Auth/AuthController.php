<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Validator;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';




    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {

       $this->middleware('guest', ['except' => ['postLogin','logout','checkAuthentication']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [ 'email' => 'required|email',
            'password' => 'required|min:6'];

        $messages = ['email.email' => 'Please enter valid email address'];

        return Validator::make($data, $rules, $messages);
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        return redirect($this->redirectPath());
    }
    
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @param Request $request
     * @param Guard $auth
     * @return string
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function postLogin(Request $request,Guard $auth)
    {

        if (!@$_COOKIE['gillie_email'] && !@$_COOKIE['gillie_password']) {
            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
        }
        if( $request["remember_me"] )
        {
            $expire=time()+60*60*24*30;//30 days
            setcookie("gillie_email", $request['email'],$expire, "/");
            setcookie("gillie_password", base64_encode($request["password"]),$expire, "/");
        }
        else
        {
            $expire=time()-60*60*24*30;//30 days
            setcookie("gillie_email", $request['email'],$expire, "/");
            setcookie("gillie_password", base64_encode($request["password"]),$expire, "/");
        }
        if( @$_COOKIE['gillie_email'] && @$_COOKIE['gillie_password'] )
        {
            $credentials = array('email'=>$_COOKIE['gillie_email'],'password'=>base64_decode($_COOKIE['gillie_password']));
        }
        else{
            $credentials = $request->only('email', 'password');
        }

        if($auth->validate($credentials, true)) {

                if ($auth->attempt($credentials, true)) {

                    $user = Auth::getLastAttempted();
                    if ($user->getIsActive() == \App\Repository\usersRepo::STATUS_ACTIVE) {
                    return 'true';
                } else {

                        return 'in_active';
                }
            }
            else
            {
                return 'false';
            }
        }
        else {
            return 'false';
        }
    }

    public function logout()
    {

      //set expiration time of cookies.
        $expire=time()-60*60*24*30;//30 days

        //unset login cookies
        setcookie("gillie_email", "", $expire, "/");
        setcookie("gillie_password", "", $expire, "/");

        Auth::logout();
        Session::flush();
        return redirect('/');
    }

    /**
     * Common function Check if user is authenticated
     *
     * @author rkaur3
     * @version 1.0
     * @return boolean 1 if authenticated else 0
     * Dated 8-sep-2016
     */
    public function checkAuthentication()
    {
        if(Auth::check())
        {
            return json_encode(array('status'=>true));
        }else
        {
            return json_encode(array('status'=>false));

        }
    }
}
