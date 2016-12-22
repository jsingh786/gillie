<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repository\adminRepo as adminRepo;
use App\Repository\usersRepo as usersRepo;
use App\Repository\newsRepo;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Hash;
use App\Http\Requests\AdminRequest;
use LaravelDoctrine\ORM\Exceptions\ExtensionNotFound;
use Mockery\CountValidator\Exception;
use Validator;

class AdminController extends Controller{

    /**
     * @var EntityManager
     */
    private $em;
    private $adminRepo;
    private $usersRepo;
    private $newsRepo;

    /*
     * Guard for admin
     */
    protected $guard = 'admin';

    public function __construct(adminRepo $adminRepo, usersRepo $usersRepo, EntityManager $em,newsRepo $newsRepo)
    {
        $this->adminRepo = $adminRepo;
        $this->usersRepo = $usersRepo;
        $this->newsRepo = $newsRepo;
        $this->em = $em;
        $this->middleware('admin', ['except' => 'getIndex']);
    }

    protected function validator(array $data , array $rules)
    {
        return Validator::make($data,$rules);
    }

    /*
     * [ Backend index controller ]
     * @author kaurGuneet
     */

    public function getIndex(){
        
        return view('backend.login');
    }

    /*
     * [ Dashboard Page Controller ]
     * @author kaurGuneet
     */
    public function getDashboard()
    {
        $admin=Auth::guard('admin')->user();
        $userCount = $this->usersRepo->getUserCount();
        $newsCount = $this->newsRepo->getNewsCount();
        return view('backend.dashboard',compact('admin','userCount','newsCount'));
    }

    /*
     * [Manage Users Controller]
     * @author kaurGuneet
     */

    public function  getUserManagement(){
        return view('backend.userManagement');
    }

    /*
     * [User Profile Management Page]
     *
     * @author kaurGuneet
     * @version 1.0
     * @date 16/june/2016
     *
     */
    public function getProfile(){
        return view('backend.profile',compact('adminDetail'));
    }

    /*
     *  [ Edit admin's Detail]
     *
     * @author kaurGuneet
     * @version 1.0
     * @date 16/june/2016
     *
     */

    public function postEditProfile(AdminRequest $request){
        try {
            $firstname = $request->firstname;
            $lastname = $request->lastname;
            $email = Auth::guard($this->guard)->user()->getEmail();

            //Call to editProfile function in adminRepo
            $obj = $this->adminRepo->editProfile($firstname, $lastname, $email);

            if ($obj) {
                //todo Make a separate helper class for string values. Define const MSG_UPDATED_SUCCESSFULLY = "Updated Successfuly!!".
                return Response::json(['status' => 'success', 'msg' => 'Updated Successfuly!!']);
            } else {
                //todo Make a separate helper class for string values. Define const MSG_NOTHING_TO_UPDATE = "Nothing to update.".
                return Response::json(['status' => 'error', 'error' => 'Nothing to update.']);
            }
        }catch (\Exception $e){

            //and in next phase we will
            return Response::json(['status' => 'exception', 'error' => 'Opps! Something went wrong.' ]);
        }

    }

    /*
     *  [Change User Password ]
     * @param: [old_password,password,confirm_password]
     * @author kaurGuneet
     * @version 1.0
     * @date 16/june/2016
     *
     */
    public function postChangePassword(Request $request) { //todo why the name of function can't be just changePassword

            //Validation Rules for change password form
            $rules = [
                'old_password' => 'required|min:6|max:20',
                'password' => 'required|min:6|max:20|regex:/^\S*$/',
                'confirm_password' => 'required|min:6|max:20|same:password'
            ];

            //call to validator function
            $validator = $this->validator($request->all(), $rules);

            //If validation fails it throughs an exception
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }

            $old_password   = $request->old_password;
            $hashedPassword = Auth::guard($this->guard)->user()->getPassword();
            $id             = Auth::guard($this->guard)->user()->getId();

            //Check for old password
            if (Hash::check($old_password, $hashedPassword)) {

                //Create new hashed password
                $password = Hash::make($request->password);

                //Call to changePassword function in adminRepo
                $obj = $this->adminRepo->changePassword($password, $id);

                if ($obj) {
                    //todo Make a separate helper class for string values.
                    return Response::json(['status' => 'success', 'msg' => 'Updated Successfuly!!']);
                } else {
                    //todo Make a separate helper class for string values.
                    return Response::json(['status' => 'error', 'error' => 'Nothing to update.']);
                }
            } else {
                //todo Make a separate helper class for string values.
                return Response::json(['status' => 'error', 'error' => 'Old Password is incorrect!!']);
            }
    }

    /*
    * [Get Admin Profile detail Api]
    *
    * @author kaurGuneet
    * @version 1.0
    * @date 16/june/2016
    * @returns JSON [Admin Detail]
    */

    public function getAdminDetail(){
        try {
            //Fetch Detail of Logged in Admin
            $adminDetail = Auth::guard($this->guard)->user();

            //Get data from object to an Array
            $adminData = array(
                'id' => $adminDetail->getId(),
                'firstname' => $adminDetail->getFirstname(),
                'lastname' => $adminDetail->getLastname(),
                'email' => $adminDetail->getEmail()
            );

            //Send Response
            return Response::json(['status' => 'success', 'data' => $adminData]);
        }catch (\Exception $e){
            return Response::json(['status'=>'exception','error' => 'Opps! Something went wrong.' ]);
        }
    }
}
?>