<?php

namespace App\Http\Controllers\AdminAuth;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;




class AdminAuthController extends Controller
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



    /*
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /*
     * Guard for admin
     */
    protected $guard = 'admin';
    /*
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('admin', ['except' => 'postLogin']);
    }

    protected function validator(array $data , array $rules, array $messages)
    {
        return Validator::make($data, $rules, $messages);
    }

    /*
     * This function consist admin login functionality
     * @author kaurGuneet
     */

    public function postLogin(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'email.required'                => 'This field is required.',
            'password.required'             => 'This field is required.',

        ];


        //call to validator function
        $validator = $this->validator($request->all(), $rules, $messages);

        $credentials = $request->only('email', 'password');

        if(Auth::guard($this->guard)->validate($credentials, true))
        {
            if(Auth::guard($this->guard)->attempt($credentials,true))
            {
                return redirect('admin/dashboard');
            }
        }
        else
        {
            return back()->withErrors(['Invalid'=>'Please enter a valid email/password.']);
        }
    }

    /*
     * Admin Logout function
     * @author kaurGuneet
     */

    public function logout(){
        Auth::guard($this->guard)->logout();
        return redirect('admin');
    }

    /*
     * Laravel reset password function override
     * @author kaurGuneet
     */

    protected function resetPassword($user, $password)
    {
        $userId=$user->getId();
        $result = $this->adminRepo->changePassword(bcrypt($password),$userId);
        if($result) {
            \Auth::guard($this->guard)->login($user);
        }
    }
    


}
