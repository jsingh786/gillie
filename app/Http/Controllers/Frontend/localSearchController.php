<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/18/2016
 * Time: 4:07 PM
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;


//Repositories.
use App\Repository\usersRepo as userRepo;
use App\Repository\userProfileRepo as userProfileRepo;
use App\Repository\cityRepo as cityRepo;
use App\Repository\stateRepo as stateRepo;
use Illuminate\Support\Facades\Config;
use App\Exceptions;
use Auth;
use Validator;
use Cookie;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

use App\Service\Users\Profile as userProfileService;
use App\Service\Elastic\Users as elasticSearchUsers;

use App\Repository\activitiesRepo as activitiesRepo;
use App\Repository\propertiesRepo as propertiesRepo;
use App\Repository\speciesRepo as speciesRepo;
use App\Repository\weaponsRepo as weaponsRepo;
use App\Repository\followwRepo as followwRepo;

class LocalSearchController extends Controller
{

    private $usersRepo;

    private $userNotesRepo;

    public $public_path;
    public $server_public_path;

    public function __construct(userRepo $usersRepo,
                                userProfileRepo $userProfileRepo,
                                userProfileService $userProfileService,
                                elasticSearchUsers $elasticSearchUsers,
                                cityRepo $cityRepo,
                                stateRepo $stateRepo,
                                activitiesRepo $activitiesRepo,
                                propertiesRepo $propertiesRepo,
                                speciesRepo $speciesRepo,
                                weaponsRepo $weaponsRepo,
                                followwRepo $followwRepo
    )
    {

        parent::__construct();
       // $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->userProfileRepo = $userProfileRepo;
        $this->userProfileService = $userProfileService;
        $this->elasticSearchUsers = $elasticSearchUsers;
        $this->cityRepo = $cityRepo;
        $this->stateRepo = $stateRepo;
        $this->activitiesRepo = $activitiesRepo;
        $this->propertiesRepo = $propertiesRepo;
        $this->speciesRepo = $speciesRepo;
        $this->weaponsRepo = $weaponsRepo;
        $this->public_path = Config::get('constants.PUBLIC_PATH');
        $this->server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
        $this->followwRepo = $followwRepo;
    }

    /**
     * Return cities in form of json encoded
     * array
     * @author hkaur5
     * @return json_encoded array of cities (['id'=>1, 'name'=>'amritsar'])
     */
    public function getCitiesByStateId($id){

        $cities =  $this->cityRepo->get($id);
        $cities_array = array();
        if($cities){
            foreach($cities as $key=>$city)
            {
                $cities_array[$key]['id'] = $city->getId();
                $cities_array[$key]['name'] = $city->getName();

            }
        }
       return json_encode($cities_array);
    }

    /**
     * Get all states from database
     * @author hkaur5
     * @return string
     */
    public function getAllStates(){

        $states =  $this->stateRepo->get();
        $states_array = array();
        if($states){
            foreach($states as $key=>$state)
            {
                $states_array[$key]['id'] = $state->getId();
                $states_array[$key]['name'] = $state->getName();

            }
        }
        return json_encode($states_array);
    }

    /**
     * Call elastic search function to get results from
     * user elastic database according to given params.
     * @author hkaur5
     * @param Request $request
     * @return json_encoded array ['name'=>'','address'=>'','']
     */
    public function getLocals( Request $request){


        $final_response = array();
      //  dd($request->all());
        if($request['city'] == 0
            && $request['state'] == 0
            && !$request['search_text']
            && !$request['species']
            && !$request['activities']
            && !$request['properties']
            && !$request['weapons']
        ){
            $final_response['users_info'] = 2;
            return json_encode($final_response);
        }

        //Parameters for elastic search.
        $params = [
            'from'=> $request['offset'],
            'size'=> $request['limit'],
            'city'=> $request['city'],
            'state'=> $request['state'],
            'weapons'=> $request['weapons'],
            'activities'=> $request['activities'],
            'species'=> $request['species'],
            'properties'=> $request['properties'],
            'search_text'=> $request['search_text'],


        ];
        $searched_results = $this->elasticSearchUsers->search($params);
        //dd($searched_results);

        if($searched_results['users'] ) {


            foreach ($searched_results['users'] as $key => $searched_user) {
                $users_array[$key]['name'] = $searched_user['_source']['firstname'] . ' ' . $searched_user['_source']['lastname'];
                $users_array[$key]['address'] = $searched_user['_source']['address'];
                $users_array[$key]['id'] = $searched_user['_id'];

                $users_array[$key]['image'] = $this->userProfileService->getUserProfilePhoto($searched_user['_id']);
                //Follow button.
                if(Auth::check()) {
                    $users_array[$key]['is_followed'] = 4;

                    if ($searched_user['_id'] != Auth::Id()) {
                        $is_followed = $this->followwRepo->isUser1followedByUser2(Auth::Id(), $searched_user['_id']);
                        if ($is_followed == true) {
                            $followingLocal = $this->followwRepo->localsFollowingUsers(Auth::Id(), $searched_user['_id']);
                            $users_array[$key]['is_followed'] = true;//following.
                            $users_array[$key]['fid'] = $followingLocal[0]->getId();
                        } else {
                            $users_array[$key]['is_followed'] = 0;//not following.
                            $users_array[$key]['fid'] = 0;
                        }
                    } else {
                        $users_array[$key]['is_followed'] = 3;//user himself.
                    }
                }
                else{
                    $users_array[$key]['is_followed'] = 4;
                }
            }
            $final_response['users_info'] = $users_array;
        }
        else{
            $final_response['users_info'] = 0;
        }


        $final_response['total_count'] = $searched_results['total_count'];
        $final_response['currentPage'] = $request['currentPage'];
        return json_encode($final_response);

    }
}