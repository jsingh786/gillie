<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repository\usersRepo as usersRepo;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\User;



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

    /*
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    private $usersRepo;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(usersRepo $usersRepo)
    {
        //$this->middleware('guest');
        $this->usersRepo = $usersRepo;
    }



    public function sendResetLinkEmail(Request $request)
    {

        $email = $request->email;
        //$rules = ['email' => 'required|email'];
        $messages = ['email.email' => 'Please enter valid email address'];

        $this->validate($request, ['email' => 'required|email'],$messages);
        
        $getUser = $this->usersRepo->getRowObject(['email',$email]);
        if($getUser) {
            $userArr = array();
            $userArr['id'] = $getUser->getId();
            $userArr['firstname'] = $getUser->getFirstname();
            $userArr['lastname'] = $getUser->getLastname();
            $userArr['email'] = $getUser->getEmail();
            $userArr['is_active'] = $getUser->getIsActive();
        }

        if(!empty($userArr)){

            $response = Password::sendResetLink($request->only('email'), function($message) use($email)
            {
                $message->to($email, 'User')->from('support@Gillie.com')->subject('Password Recovery Link!');
            });

            switch ($response)
            {
                case PasswordBroker::RESET_LINK_SENT:
                    //return redirect()->back()->with('status', trans($response));
                    return json_encode(array('status'=>200,'message'=>'A recovery email has been sent to your email address'));

                case PasswordBroker::INVALID_USER:
                    return redirect()->back()->withErrors(['email' => trans($response)]);
            }

            return json_encode(array('status'=>200,'message'=>'A recovery email has been sent to your email address'));
        }else{
            return json_encode(array('status'=>301,'message'=>'This email address does not belong to Gillie Network.'));
        }
    }

    public function resetPassword($user, $password)
    {
        $userId=$user->getId();
        $result = $this->usersRepo->changePassword(bcrypt($password),$userId);
        if($result) {
           // \Auth::guard($this->guard)->login($user);
            \Auth::login($user);
        }
    }

    public function postReset(Request $request)
    {

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $userRole=User::where('email',$request->email)->get()->toArray();

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });


        switch ($response) {
            case Password::PASSWORD_RESET:
                //$flashObj = (new FlashHelper)->success(null,trans($response))->toJson();

                    return redirect('/');

            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

            return view('auth.passwords.reset')->with('token', $token);
      
    }
    
}
