<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Repository\adminRepo as adminRepo;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBroker;
use Validator;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    // override default properties
    protected $redirectTo = 'admin/dashboard';

    protected $linkRequestView = 'admin.password.email';

    protected $emailView='adminauth.emails.password';

    protected $resetView = 'adminauth.passwords.reset';

    protected $guard = 'admin';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(adminRepo $adminRepo)
    {
        $this->middleware('guest');
        $this->adminRepo = $adminRepo;
        \Config::set("auth.defaults.passwords","admin");
    }
    
    public  function getEmail(){
        return view('backend/reset');
    }

    protected function validator(array $data, array $rules)
    {
        return Validator::make($data, $rules);
    }


    public function postEmail(Request $request)
    {

        $email = $request->email;

        //Validation Rules for change password form
        $rules = [ 'email' => 'required|email'];

        //call to validator function
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' =>'Please enter your email id.']);
        }
        $getRole = $this->adminRepo->getAdminByEmail($email);

        if($getRole) {
            $adminArr = array();
            $adminArr['id'] = $getRole->getId();
            $adminArr['firstname'] = $getRole->getFirstname();
            $adminArr['lastname'] = $getRole->getLastname();
            $adminArr['email'] = $getRole->getEmail();
            $adminArr['is_active'] = $getRole->getIsActive();
        }

        if(!empty($adminArr)){

            $response = Password::sendResetLink($request->only('email'), function($message) use($email)
            {
                $message->to($email, 'Gillie-Admin')->from('support@Gillie.com')->subject('Password Recovery Link!');
            });

            switch ($response)
            {
                case PasswordBroker::RESET_LINK_SENT:
                    return redirect()->back()->with('status', trans($response));

                case PasswordBroker::INVALID_USER:
                    return redirect()->back()->withErrors(['email' => trans($response)]);
            }
        }else{
            return redirect()->back()->withErrors(['email' => 'Email does not exist.']);
        }

    }


    public function resetPassword($user, $password)
    {
        $userId=$user->getId();
        $result = $this->adminRepo->changePassword(bcrypt($password),$userId);
        if($result) {
            \Auth::guard($this->guard)->login($user);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('adminauth.passwords.reset')) {
            return view('adminauth.passwords.reset')->with(compact('token', 'email'));
        }
        
    }

    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|max:20|regex:/^\S*$/',
        ];
    }
}
