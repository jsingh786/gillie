<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 8/25/2016
 * Time: 3:52 PM
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Repository\usersRepo as usersRepo;
use App\Repository\countryRepo as countryRepo;
use App\Repository\cityRepo as cityRepo;
use App\Repository\stateRepo as stateRepo;
use App\Service\Elastic;
use Auth;
use App\Mailers\Frontend\UserMailer as userMailers;
use App\Service\Elastic\Users as elasticSearchUsers;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;



class HomeController extends Controller{

    private $usersRepo;
    
    public function __construct(usersRepo $usersRepo,
                                elasticSearchUsers $elasticSearchUsers,
                                countryRepo  $countryRepo,cityRepo $cityRepo,stateRepo $stateRepo)
    {
        parent::__construct();
        $this->middleware('guest',['except' => ['dirPagination','thankyou']]);
        $this->usersRepo = $usersRepo;
        $this->elasticSearchUsers = $elasticSearchUsers;
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cityRepo;
        $this->stateRepo = $stateRepo;
    }

    /**
     * Home/Welcome Page
     *
     * @return view
     * @author rkaur3
     * @version 1.0
     * Dated 26-aug-2016
     */
    public function index()
    {
        if (@$_COOKIE['gillie_email'] && @$_COOKIE['gillie_password']) {

            return redirect('auth/login');
        }
        else
        {
            return view('frontend.welcome')->with('enable_profile_menu', false);
        }

    }

    /**
     * Sign Up Popup
     *
     * @return sign view for popup
     * @author rkaur3
     * @version 1.0
     * Dated 26-aug-2016
     */
    public function signup()
    {
        return view('frontend.signup');
    }

    /*
     * Saves user into database
     *
     * @param Request $request
     * @author rkaur3
     * @version 1.0
     */
    public function saveUser(UserRequest $request)
    {

        $userData = $request->all();
        if ($userData['country']) {
            $countryId = $this->countryRepo->addCountry($userData['country']);
            if ($countryId) {
                $data['country_id'] = $countryId;
                if ($userData['state']) {
                    $stateId = $this->stateRepo->addState($userData['state'], $countryId->getId());
                    if ($stateId) {
                        $data['state_id'] = $stateId;
                    }
                    else{
                        $data['state_id'] = "";
                    }

                }
                if ($userData['city']) {
                    $cityId = $this->cityRepo->add($userData['city'], $countryId, $stateId->getId());
                    if ($cityId) {
                        $data['city_id'] = $cityId;
                    } else {
                        $data['city_id'] = "";
                    }
                }
            }
            else{
                $data['country_id'] = "";
            }
        }

        $data['email'] = $userData['email'];
        $data['firstname'] = $userData['firstname'];
        $data['lastname'] = $userData['lastname'];
        $data['password'] = $userData['password'];
        $data['zipcode'] = $userData['zipcode'];
        $data['is_active'] = $userData['is_active'];
        $data['longitude'] = $userData['longitude'];
        $data['latitude'] = $userData['latitude'];
        $data['address'] = $userData['address'];

        $saveUser = $this->usersRepo->saveUser($data);
        $user_obj = $this->usersRepo->getRowObject(['id', $saveUser]);
//        $elastic_response = $this->elasticSearchUsers->createUser($user_obj);
        userMailers::sendActivationEmail($user_obj);
        if ($saveUser) {
            //send email for user activation
             return 'true';
        }
    }

    /**
     * login view for popup
     *
     * @author rkaur3
     * @version 1.0
     * Dated 30-aug-2016
     */
    public function login()
    {
        return view('frontend.login');
    }

    /**
     * forgot pwd view for popup
     *
     * @author rkaur3
     * @version 1.0
     * Dated 30-aug-2016
     */
    public function forgotPwd()
    {
        return view('frontend.forgot-pwd');
    }

    /**
     * Thank you dialog when signed up successfully
     *
     * @author rkau3
     * @version 1.0
     * Dated 31-aug-2016
     */
    public function thankyou()
    {
        return view('frontend.common.thankyou');
    }

    /**
     * Common function to return pagination
     * control view
     * @author rkaur3
     * @version 1.0
     * Dated 5-sep-2016
     */
    public function dirPagination()
    {
    
        return view('frontend.common.dir-pagination');
    }

    /**
     * Activate user
     * Set user status to 1.
     * @author hkaur5
     */
    public function activateUser($id)
    {
        $user_obj = $this->usersRepo->getRowObject(['id',base64_decode($id)]);

        if($user_obj->getIsActive() == 0)
        {
            $elastic_response = $this->elasticSearchUsers->createUser($user_obj);
            $user_id = $this->usersRepo->saveUser(array('user_id'=>$user_obj->getId(),'is_active'=>\App\Repository\usersRepo::STATUS_ACTIVE));

            if($user_obj->getIsActive())
            {
                $msg = 'Your profile is activated successfully! Login to continue.';
            }
            else{
                 $msg = 'Some error occurred while activating your profile. Please try again.';
            }
        }
        else
        {
            $msg = 'User is already activated.';
        }

       return redirect('/');
    }

    /**
     * Search user on basis of firstname
     * @author ssharma4
     */

    public function searchuser()
    {
        $elastic_users_obj = new Elastic\Users();
        $usersList = $elastic_users_obj->searchUser('vats-1990@mailinator.com');
        dd($usersList);

    }
    
    
}