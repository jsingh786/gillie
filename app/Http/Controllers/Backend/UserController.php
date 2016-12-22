<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 6/15/2016
 * Time: 11:07 AM
 */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\Repository\usersRepo;

class UserController extends  Controller{

    /**
     * @var usersRepo
     */
    private $usersRepo;

    public function __construct(usersRepo $usersRepo)
    {
        //Contructor Function
        $this->usersRepo = $usersRepo;
        $this->middleware('admin');
    }

    /**
     * Get user listing/Manage users
     *
     * @author rkaur3
     * @version 1.0
     * @Date 15th June, 2016
     */
    public function index(Request $request)
    {
        //check if it is http ajax request
        if($request->ajax())
        {
            //filter for server side search
            $searchParam    = (null !== Input::get('searchParam')) ? Input::get('searchParam') : '';
            //offset set to 0 by default
            $start          = (null !== Input::get('start')) ? Input::get('start') : 0;
            //length/limit specified for listing records
            $length         = (null !== Input::get('length')) ? Input::get('length') : 1;

            $users = $this->usersRepo->getAllUsers($request->all(), $searchParam, $start, $length);
            $user_arr = array();
            foreach($users as $key=>$user)
            {
                $user_arr[$key]['id'] = $user->getId();
                $user_arr[$key]['firstname'] = $user->getFirstname();
                $user_arr[$key]['lastname'] = $user->getLastname();
                $user_arr[$key]['email'] = $user->getEmail();
                $user_arr[$key]['updated_at'] = $user->getUpdatedAt();
            }
          
            $list['recordsTotal'] = $this->usersRepo->getUserCount();
            if($searchParam != "")
            {
                $list['recordsFiltered'] = count($users);
            }
            else
            {
                $list['recordsFiltered'] = $this->usersRepo->getUserCount();
            }
            $list['draw'] = (Input::has('draw') ? (int)Input::get('draw') : 0);
            $list['data'] =  $user_arr;
            $userList = json_encode($list);
            return $userList;
        }
        return view('backend/manage-users');
    }

    /**
     * Manage add/edit form for user
     *
     * @param integer $userId(optional)
     * @return view
     * @author rkaur3
     * @version 1.0
     *
     * Dated 17 June,2016
     */
    public function addUser( $userId = null )
    {
       return view('backend/add-user',compact('userId'));
    }

    /**
     * Save new user into database/Update User
     *
     * @author rkaur3
     * @version 1.0
     * @Date 17th June,2016
     */
    public function saveNewUser(UserRequest $request)
    {
        try {
            $userData = $request->all();
            $user_id = $this->usersRepo->saveUser($userData);
            if ($user_id) {
                if (isset($userData['user_id']) && $userData['user_id'] != "") {

                    return Response::json(['status' => 'update', 'msg' => 'User has been updated successfully']);
                } else {
                    return Response::json(['status' => 'save', 'msg' => 'User has been saved successfully']);
                }
            }
        }
        catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }

    }

    /**
     * Get user details
     *
     * @param integer $userId
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 17June,2016
     */
    public function getUserDetails($userId)
    {
        try {
            if (isset($userId) && $userId != "")
            {
                $userObj = $this->usersRepo->getRowObject(['id', $userId]);
                $userArr = array();
                $userArr['firstname'] = $userObj->getFirstname();
                $userArr['lastname'] = $userObj->getLastname();
                $userArr['email'] = $userObj->getEmail();
                $userArr['zipcode'] = $userObj->getZipcode();
                $userArr['address'] = $userObj->getAddress();
                $userArr['gender'] = $userObj->getGender();
                $userArr['is_active'] = $userObj->getIsActive();
                $userArr['status'] = $this->usersRepo->getStatusName($userObj->getIsActive());
                $userArr['gender_name'] = $this->usersRepo->getGenderName($userObj->getGender());
                return json_encode($userArr);
            }
        }
        catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }

    }

    /**
     * Soft deletes user
     *
     * @param array Request $request
     * @return json
     * @author rkaur3
     * @version 1.0
     * Dated 20june,2016
     */
    public function deleteUser(Request $request)
    {
        try {
            $data = $request->all();
            $userId = $data['user_id'];
            if ($userId != "")
            {
                $deleteUser = $this->usersRepo->deleteUser($userId);
                return Response::json(['status' => 'success', 'msg' => 'User has been deleted successfully']);
            }
        }catch (\Exception $e) {
            return Response::json(['status' => 'exception', 'error' => 'Oops! Something went wrong.' ]);
        }
    }

    /**
     * View user detail form
     *
     * @param integer $userId
     * @return view
     * @author rkaur3
     * @version 1.0
     * Dated 20June,2016
     */
    public function viewUserDetails( $userId )
    {
        return view('backend/view-user',compact('userId'));
    }
}

