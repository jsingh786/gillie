<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/5/2016
 * Time: 4:17 PM
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;

//Repositories.
use App\Repository\usersRepo as userRepo;
use App\Repository\userActivitiesRepo as userActivitiesRepo;
use App\Repository\userWeaponsRepo as userWeaponsRepo;
use App\Repository\userSpeciesRepo as userSpeciesRepo;
use App\Repository\userPropertiesRepo as userPropertiesRepo;
use App\Repository\userHuntingLandRepo as userHuntingLandRepo;
use App\Repository\userProfileRepo as userProfileRepo;
use App\Repository\weaponsRepo as weaponRepo;
use App\Repository\userNotesRepo as userNotesRepo;
use App\Repository\albumRepo as albumRepo;
use App\Repository\photosRepo as photosRepo;
use App\Repository\huntingLandRepo as huntingLandRepo;
use App\Repository\countryRepo as countryRepo;
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

class ProfileController extends Controller
{

    private $usersRepo;

    private $userNotesRepo;

    public function __construct(userRepo $usersRepo,
                                userActivitiesRepo $userActivitiesRepo,
                                userWeaponsRepo $userWeaponsRepo,
                                userSpeciesRepo $userSpeciesRepo,
                                UserPropertiesRepo $userPropertiesRepo,
                                userHuntingLandRepo $userHuntingLandRepo,
                                userProfileRepo $userProfileRepo,
                                weaponRepo $weaponRepo,
                                albumRepo $albumRepo,
                                photosRepo $photosRepo,
                                userNotesRepo $userNotesRepo,
                                huntingLandRepo $huntingLandRepo,
                                userProfileService $userProfileService,
                                elasticSearchUsers $elasticSearchUsers,
                                countryRepo  $countryRepo,cityRepo $cityRepo,stateRepo $stateRepo
                                )
    {

        parent::__construct();
        $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->userActivitiesRepo = $userActivitiesRepo;
        $this->userWeaponsRepo = $userWeaponsRepo;
        $this->userSpeciesRepo =  $userSpeciesRepo;
        $this->userPropertiesRepo = $userPropertiesRepo;
        $this->userHuntingLandRepo = $userHuntingLandRepo;
        $this->userProfileRepo = $userProfileRepo;
        $this->weaponRepo = $weaponRepo;
        $this->albumRepo = $albumRepo;
        $this->photosRepo = $photosRepo;
        $this->userNotesRepo = $userNotesRepo;
        $this->huntingLandRepo = $huntingLandRepo;
        $this->userProfileService = $userProfileService;
        $this->elasticSearchUsers = $elasticSearchUsers;
        $this->countryRepo = $countryRepo;
        $this->cityRepo = $cityRepo;
        $this->stateRepo = $stateRepo;
    }

    /* Render view for About Me section
     * @author rawatabhishek
     */
    public function index($user_id)
    {
        $params = array('id', $user_id);
        $user_obj =  $this->userRepo->getRowObject($params);
        return view('frontend/about-me')->with('enable_profile_menu', true)->with('profileHolderObj', $user_obj);
    }




    /**
     * Render profile-edit blade if user's own profile is accessed
     * else redirect to news feed controller for rendering user's (whose id is
     * passed) newsfeed within profile section.
     *
     * @param $id (user id whose profile view will be rendered)
     * @author hkaur5
     * @author rawatAbhishek (added redirection code and parameterised the function)
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function profile($id)
    {
        $params = array('id', $id);
        $user_obj =  $this->userRepo->getRowObject($params);

       //Show News feed to the user, if the user is visiting some other user's profile
        if(Auth::Check()){
            if(Auth::Id() != $user_obj->getId() )
            {
                return redirect('about-me/'.$user_obj->getId());
            }
        }
        return view('frontend/profile-edit')->with('enable_profile_menu', true)->with('profileHolderObj', $user_obj);
    }

    /**
     * Fetch user's information from users and user_profile user tables.
     * @author rawatabhishek
     * @author hkaur5 (Added default profile photo and profile photo param in return array.)
     * @param integer $id
     * @return JSON
     * @version 1.0
     */
    public function getUserBasicInfo($id)
    {

        $params     = array('id', $id);
        $user_obj   = $this->userRepo->getRowObject($params);
        $user_info  = array();
        if($user_obj)
        {
            if ($user_obj->getFirstname() && $user_obj->getLastname()) {
                $user_info['name']          = $user_obj->getFirstname().' '.$user_obj->getLastname();
            }

            if ($user_obj->getUserProfile()) {
                $user_info['profileobj']    = $user_obj->getUserProfile();
            }

            if ($user_obj->getEmail()){
                $user_info['email']         = $user_obj->getEmail();
            }

            if ($user_obj->getAddress()) {
                $user_info['address']       = $user_obj->getAddress();
            }

            if ($user_obj->getGender()) {
                $user_info['gender']        = $user_obj->getGender();
            }


            if ($user_obj->getUserProfile())
            {
                if($user_obj->getUserProfile()->getPhoneNumber())
                {
                    $user_info['phone'] = $user_obj->getUserProfile()->getPhoneNumber();
                }

                if($user_obj->getUserProfile()->getOccupation())
                {
                    $user_info['occupation'] = $user_obj->getUserProfile()->getOccupation();
                }

                if($user_obj->getUserProfile()->getWork())
                {
                    $user_info['work'] = $user_obj->getUserProfile()->getWork();
                }

                if($user_obj->getUserProfile()->getDob())
                {
                    $user_info['dob'] = $user_obj->getUserProfile()->getDob()->format('M d, Y');
                }

                if($user_obj->getUserProfile()->getMarital_status())
                {
                    $user_info['marital_status']= $user_obj->getUserProfile()->getMarital_status();
                }
            }

            $user_info['profile_photo'] = $this->userProfileService->getUserProfilePhoto($id);

            //Default profile picture required for croppic plugin.
            if($user_obj->getGender() == 1){
                $user_info['default_profile_photo'] =  Config::get('constants.PUBLIC_PATH').'/frontend/images/default_prof_photo_male.png';
            }
            else if($user_obj->getGender() == 2){
                $user_info['default_profile_photo'] =  Config::get('constants.PUBLIC_PATH').'/frontend/images/default_prof_photo_female.png';
            }
            else{
                $user_info['default_profile_photo'] =  Config::get('constants.PUBLIC_PATH').'/frontend/images/default_prof_photo_male.png';
            }
            return json_encode($user_info);
        }
        return json_encode(0);
    }

    /**
     * Get user profile image.
     * @author rawatabhishek
     * @param integer $userId(userId of logged in user)
     * @return JSON
     * @version 1.0
     */
    public function getProfileImage(Request $request)
    {
        print_r($request->all()); die;
        $result = $this->userProfileService->getUserProfilePhoto($request->userId);
        if($result){
            return json_encode($result);
        }
    }

    /**
     * Fetch user's information from various user tables.
     * @author hkaur5
     * @param Request $request
     * @return json_encoded user_info array or 0
     */
    public function getUserInfo(Request $request)
    {
        $params = array('id',Auth::id());
        $user_obj = $this->userRepo->getRowObject($params);
        $user_info = array();

        // If user object exists.
        if($user_obj)
        {
            //Get activities
            $user_activities = $this->userActivitiesRepo->get($user_obj->getId());
            if ($user_activities)
            {
                foreach($user_activities as  $key=>$user_activity)
                {
                    $user_info['activities']['ids'][$key] = $user_activity->getActivities()->getId();
                }
            }
            else
            {
                $user_info['activities'] = 0;
            }

            //Get Weapons
            $user_weapons = $this->userWeaponsRepo->get($user_obj->getId());
            if($user_weapons)
            {

                foreach($user_weapons as  $key=>$user_weapon)
                {

                    $user_info['weapons']['ids'][$key] = $user_weapon->getWeapons()->getId();
                    $user_info['weapons']['names'][$user_weapon->getWeapons()->getId()] = $user_weapon->getWeapons()->getName();
                }
            }
            else
            {
                $user_info['weapons'] = 0;
            }

            //Get favorite Weapon.
            $user_fav_weapons =$this->userWeaponsRepo->get($user_obj->getId(),true);
            if($user_fav_weapons)
            {
                $user_info['fav_weapon_id']= $user_fav_weapons[0]->getWeapons()->getId();
                $user_info['fav_weapon_name']= $user_fav_weapons[0]->getWeapons()->getName();
            }
            else
            {
                $user_info['fav_weapons_id']= 0;
                $user_info['fav_weapons_name']= '';
            }

            //Get all weapons
            $weapons = $this->weaponRepo->getAll();
            foreach($weapons as $key=>$weapon)
            {
                $user_info['all_weapons'][$weapon->getId()]['name'] = $weapon->getName();


            }

            //Get Species.
            $user_species = $this->userSpeciesRepo->get($user_obj->getId());

            if($user_species)
            {

                foreach($user_species as  $key=>$species)
                {
                    $user_info['species']['ids'][$key] = $species->getSpecies()->getId();
                }
            }
            else
            {
                $user_info['species'] = 0;
            }

            //Get Properties.
            $user_properties = $this->userPropertiesRepo->get($user_obj->getId());
            if($user_properties)
            {

                foreach($user_properties as  $key=>$user_property)
                {
                    $user_info['properties']['ids'][$key] = $user_property->getProperties()->getId();
                }
            }
            else
            {
                $user_info['properties'] = 0;
            }


            //Get all hunting_lands
            $huntingLands = $this->huntingLandRepo->getAll();
            foreach($huntingLands as $key=>$huntingLand)
            {
                $user_info['all_hunting_lands'][$huntingLand->getId()]['name'] = $huntingLand->getName();


            }
            //Get Hunting lands.
            $user_hunting_lands = $this->userHuntingLandRepo->get($user_obj->getId());
            if($user_hunting_lands)
            {

                foreach($user_hunting_lands as  $key=>$user_hunting_land)
                {
                    $user_info['hunting_lands']['names'][$user_hunting_land->getHuntingLand()->getId()] = $user_hunting_land->getHuntingLand()->getName();
                }
            }
            else
            {
                $user_info['hunting_lands'] = 0;
            }

            //Get favorite Hunting lands.
            $user_fav_hunting_land = $this->userHuntingLandRepo->get($user_obj->getId(), true);
            if($user_fav_hunting_land)
            {
                $user_info['fav_hunting_land_id']= $user_fav_hunting_land[0]->getHuntingLand()->getId();
                $user_info['fav_hunting_land_name']= $user_fav_hunting_land[0]->getHuntingLand()->getName();
            }
            else
            {
                $user_info['fav_hunting_land_id']= 0;
                $user_info['fav_hunting_land_name']= '';
            }
            $user_info['firstname'] = $user_obj->getFirstname();
            $user_info['lastname'] = $user_obj->getLastname();
            $user_info['name'] = $user_obj->getFirstname().' '.$user_obj->getLastname();
            $user_info['email'] = $user_obj->getEmail();
            $user_info['city'] = $user_obj->getCity();
            $user_info['state'] = $user_obj->getState();
            $user_info['country'] = $user_obj->getCountry();
            $user_info['longitude'] = $user_obj->getLongitude();
            $user_info['latitude'] = $user_obj->getLatitude();
            $user_info['zip_code'] = $user_obj->getZipcode();
            $user_info['address'] = $user_obj->getAddress();
            $user_info['gender'] = $user_obj->getGender();

            if($user_obj->getUserProfile())
            {
                if( $user_obj->getUserProfile()->getMarital_Status())
                {
                    $user_info['marital_status'] =  $user_obj->getUserProfile()->getMarital_Status();
                }
                else
                {
                    $user_info['marital_Status'] = 1;
                }
            }
            $user_info['address'] = $user_obj->getAddress();
            if($user_obj->getUserProfile()){$user_info['work'] = $user_obj->getUserProfile()->getWork();}
            if($user_obj->getUserProfile()){$user_info['phone'] = $user_obj->getUserProfile()->getPhoneNumber();}
            if($user_obj->getUserProfile()){$user_info['occupation'] = $user_obj->getUserProfile()->getOccupation();}
            if($user_obj->getUserProfile()){$user_info['school'] = $user_obj->getUserProfile()->getSchool();}
            if($user_obj->getUserProfile()){$user_info['college'] = $user_obj->getUserProfile()->getCollege();}
            if($user_obj->getUserProfile())
            {
                if($user_obj->getUserProfile()->getDob())
                {
                    $user_info['dob'] = $user_obj->getUserProfile()->getDob()->format('M, d Y');
                }
            }
            $user_info['profile_photo'] = $this->userProfileService->getUserProfilePhoto(Auth::Id());

            return json_encode($user_info);
        }
        else
        {
            return json_encode(0);
        }


    }

    /**
     * Update user's profile information.
     * @param UserProfileRequest $request
     * @return array ( for eg: array("success"=>"", "message"=>""),
     * success viz 1,2 and 3. 1 for successful, 2 for failure. )
     */
    public function update(UserprofileRequest $request)
    {
        if(Auth::Id())
        {
            try{
                //Storing user data===========================================
                $user_repo_array = array();
                if($request['firstname']){
                    $user_repo_array['firstname'] = $request['firstname'];

                }
                if($request['lastname']){
                    $user_repo_array['lastname'] = $request['lastname'];
                }
                $user_repo_array['user_id'] = Auth::Id();
                $user_repo_array['longitude'] = $request['lng'];
                $user_repo_array['latitude'] = $request['lat'];
//                $user_repo_array['city'] = $request['city'];
//                $user_repo_array['state'] = $request['state'];
//                $user_repo_array['country'] = $request['country'];
                $user_repo_array['zip_code'] = $request['zip_code'];
                $user_repo_array['address'] = $request['address'];
                $user_repo_array['gender'] = $request['gender'];
                //Storing user data -  END ===================================

                //Storing user profile data====================================
                $user_profile_data_arr = array();
                $user_profile_data_arr['phone'] = $request['phone'];
                $user_profile_data_arr['school'] = $request['school'];
                $user_profile_data_arr['college'] = $request['college'];
                $user_profile_data_arr['occupation'] = $request['occupation'];
                $user_profile_data_arr['work'] = $request['work'];
                $user_profile_data_arr['marital_status'] = $request['marital_status'];
                //if(array_key_exists('dob',$request )) {
                $user_profile_data_arr['dob'] = $request['dob'];
                $user_profile_data_arr['user_id'] = Auth::Id();

               // }
                //Storing user profile data - END ==============================

                //Storing user's activities Data
                $activities_data = array();
                if(!empty($request['activities'])) {
                    foreach ($request['activities'] as $key => $activity) {
                        $activities_data[$key]['activity_id'] = $activity;
                        $activities_data[$key]['user_id'] = Auth::Id();
                    }
                }

                //Storing user's species Data
                $species_data = array();
                if(!empty($request['species'])) {
                    foreach ($request['species'] as $key => $species) {
                        $species_data[$key]['species_id'] = $species;
                        $species_data[$key]['user_id'] = Auth::Id();
                    }
                }

                //Storing user's properties Data
                $properties_data = array();
                if(!empty($request['properties'])) {
                    foreach($request['properties'] as $key=>$property)
                    {
                        $properties_data[$key]['property_id'] =  $property;
                        $properties_data[$key]['user_id'] =  Auth::Id();
                    }
                }

                //Storing user's weapon's Data
                $weapons_data = array();
                if(!empty($request['weapons'])) {
                    foreach ($request['weapons'] as $key=>$weapon)
                    {
                        if ($weapon == $request['fav_weapon']){
                            $weapons_data[$key]['favorite'] = 1;
                        }else{
                            $weapons_data[$key]['favorite'] = 0;
                        }
                        $weapons_data[$key]['weapon_id'] = $weapon;
                        $weapons_data[$key]['user_id'] = Auth::id();
                    }
                }

                //Storing user's hunting land Data
                $hunting_lands_data = array();
                if(!empty($request['hunting_lands'])) {
                    foreach ($request['hunting_lands'] as $key=>$hunting_land)
                    {
                        if ($hunting_land == $request['fav_hunting_land']){
                            $hunting_lands_data[$key]['favorite'] = 1;
                        }else{
                            $hunting_lands_data[$key]['favorite'] = 0;
                        }
                        $hunting_lands_data[$key]['hunting_land_id'] = $hunting_land;
                        $hunting_lands_data[$key]['user_id'] = Auth::id();
                    }
                }

                //Saving data for fav hunting land
                if($request['fav_hunting_land'])
                {
                    $fav_hunting[1]['hunting_land_id'] = $request['fav_hunting_land'];
                    $fav_hunting[1]['user_id'] = Auth::id();
                    $fav_hunting[1]['favorite'] = 1;
                }

                //Saving and updating all records related to user profile.
                //Calling model queries.

                //Saving user activities .
                if(!empty($activities_data)) {
                    $this->userActivitiesRepo->delete(Auth::Id());
                    $this->userActivitiesRepo->add($activities_data);
                }
                else{
                    $this->userActivitiesRepo->delete(Auth::Id());
                }
                //Saving Properties
                if(!empty($properties_data)) {
                    $this->userPropertiesRepo->delete(Auth::Id());
                    $this->userPropertiesRepo->add($properties_data);
                }else{
                    $this->userPropertiesRepo->delete(Auth::Id());
                }

                //Saving user's species.
                if(!empty($species_data)) {
                    $this->userSpeciesRepo->delete(Auth::Id());
                    $this->userSpeciesRepo->add($species_data);
                }else{
                    $this->userSpeciesRepo->delete(Auth::Id());
                }

                //Saving user's weapons
                if(!empty($weapons_data)) {
                    $this->userWeaponsRepo->delete(Auth::Id());
                    $this->userWeaponsRepo->add($weapons_data);
                }else{
                    $this->userWeaponsRepo->delete(Auth::Id());
                }

                //Saving user's hunting lands.
                if(!empty($fav_hunting)) {
                    $this->userHuntingLandRepo->delete(Auth::Id());
                   // $this->userHuntingLandRepo->add($hunting_lands_data);
                    $this->userHuntingLandRepo->add($fav_hunting);
                }
                //Saving user info in users Repo.
                if(!empty($user_repo_array)){
                    if ($request['country']){
                        $countryObj = $this->countryRepo->addCountry($request['country']);

                      if ($countryObj)  {
                          $user_repo_array['country_id'] = $countryObj;
                          $stateObj = $this->stateRepo->addState($request['state'], $countryObj->getId());

                          if($stateObj) {
                              $user_repo_array['state_id'] = $stateObj;
                              $cityObj = $this->cityRepo->add($request['city'], $countryObj->getId(), $stateObj->getId());
                              if($cityObj){
                                  $user_repo_array['city_id'] = $cityObj;
                              }

                          }
                      }

                    }
                    $this->userRepo->saveUser($user_repo_array);
                }

                //Saving user profile info.
                if(!empty($user_profile_data_arr))
                {
                    //Check if user_profile record already exist then update else add record.
                    $user_profile_obj = $this->userProfileRepo->getRowObject(['users',Auth::Id()]);
                    if($user_profile_obj){
                        $this->userProfileRepo->update($user_profile_data_arr);
                    }
                    else{
                        $this->userProfileRepo->add($user_profile_data_arr);
                    }
                }

                //Update user elastic search database.
                $user_obj = $this->userRepo->getRowObject(['id',Auth::Id()]);//get user object
                $this->elasticSearchUsers->createUser($user_obj);

                return json_encode(array('success'=>1, "message"=>"Profile updated."));
            }//Try ends.

            catch(Exception $e) {
                return json_encode(array("success"=>0, "message"=>$e));
            }

        }
        else
        {
            return json_encode(array("success"=>0, "message"=>"Please login to continue."));
        }

        die;
    }


    /**
     * Used from "Croppic with laravel tutorial".
     *
     * This function validate and save image uploaded using croppic plugin
     * if image is valid it saves it to temp storage for profile photos
     * using image intervention.
     * @author hkaur5
     * @return json_encoded array  ('status' =>'success' , 'height'=>'', 'url'=>'public path of image'
     * 'width'=>'') if successfully uploaded, else return array('status'=>'error','message'=>'as per validation applied').
     * @throws \Exception
     * @see https://tuts.codingo.me/upload-and-edit-image-using-croppic-jquery-plugin
     */
    public function uploadProfilePhoto()
    {
        $rel_image_path        = Config::get('constants.REL_IMAGE_PATH');
        $server_public_path    = Config::get('constants.SERVER_PUBLIC_PATH');
        $public_path           = Config::get('constants.PUBLIC_PATH');

        $form_data = Input::all();

        //Validations for profile photo file.
        $rules = [
            'img' => 'required|mimes:png,gif,jpeg,jpg'
        ];
        $messages = [
            'img.required' => \Config::get('constants.image_is_required'),
            'img.mime' => \Config::get('constants.uploaded_file_is_not_in_image_format')
        ];

        //Validate
       $validator = Validator::make($form_data, $rules, $messages);

        // If validator fails return error.
       if ($validator->fails()) {

            return json_encode([
                'status' => 'error',
                'message' => $validator->messages()->first(),
            ], 200);

        }

        $photo = $form_data['img'];

        $original_name = $photo->getClientOriginalName();

        //Get unique file name.
        $filename = \Helper_common::getUniqueNameForFile($original_name);

        //Using image intervention for saving and encoding image.
        $manager = new ImageManager();
        $userDirectory      = $rel_image_path.'\\profile_photos\\temp_storage\\user_'.Auth::Id();
        if ( !file_exists( $userDirectory ) ) {
            mkdir($userDirectory, 0777, true);
        }
        //Save image at temp location.
        $image = $manager->make( $photo )->encode('jpg')->save($server_public_path.'/frontend/images/profile_photos/temp_storage/user_'.Auth::Id().'/'. $filename );

        //Return error if not saved.
        if( !$image) {

            return Response::json([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        //Else return image path, status, width and height with 200 status.
        return json_encode([
            'status'    => 'success',
            'url'       => $public_path.'/frontend/images/profile_photos/temp_storage/user_'.Auth::Id().'/'. $filename,
            'width'     => $image->width(),
            'height'    => $image->height()
        ], 200);
    }

    /**
     *  Using image intervention resize, crop, rotate and save image.
     *  Delete image from temp location and save to user's respective folder
     *  in profile_photo folder.
     * @author hkaur5
     * @return json_encoded array  ('status' =>'success' , 'url'=>'public path of image'
     * ) if successfully uploaded, else if image not saved return array('status'=>'error','message'=>'').
     * @return string
     */
    public function saveCroppedProfilePhoto()
    {
        $rel_image_path        = Config::get('constants.REL_IMAGE_PATH');
        $server_public_path    = Config::get('constants.SERVER_PUBLIC_PATH');
        $public_path           = Config::get('constants.PUBLIC_PATH');
        $userDirectory         = $rel_image_path.'\\profile_photos\\user_'.Auth::Id();
        if ( !file_exists( $userDirectory ) ) {
            mkdir($userDirectory, 0777, true);
        }

        $form_data = Input::all();
        $image_url = $form_data['imgUrl'];

        // resized sizes
        $imgW = $form_data['imgW'];
        $imgH = $form_data['imgH'];
        // offsets
        $imgY1 = $form_data['imgY1'];
        $imgX1 = $form_data['imgX1'];
        // crop box
        $cropW = $form_data['width'];
        $cropH = $form_data['height'];
        // rotation angle
        $angle = $form_data['rotation'];

        $filename_array = explode('/', $image_url);
        $filename = $filename_array[sizeof($filename_array)-1];

        //Resize, rotate, crop and save image using image intervention library.
        $manager = new ImageManager();
        $image = $manager->make( $image_url );
        $image->resize($imgW*2, $imgH*2)->rotate(-$angle)->crop($cropW*2, $cropH*2, $imgX1*2, $imgY1*2)->save($server_public_path.'/frontend/images/profile_photos/temp_storage/user_'.Auth::Id().'/' . $filename);
        @rename( $server_public_path.'/frontend/images/profile_photos/temp_storage/user_'.Auth::Id().'/' . $filename,
            $userDirectory.'\\'.$filename );

        //Remove file from temp directory.
        \Helper_common::deleteDir($server_public_path.'/frontend/images/profile_photos/temp_storage/user_'.Auth::Id());

        //Save image profile name to database.
        $user_obj = $this->userRepo->getRowObject(['id',Auth::Id()]);

        if($user_obj->getUserProfile())
        {
            $this->userProfileRepo->update(['profile_photo_name'=>$filename,'user_id'=>Auth::Id()]);
        }
        else{
            $this->userProfileRepo->add(['profile_photo_name'=>$filename,'user_id'=>Auth::Id()]);
        }

        if( !$image) {

            return json_encode([
                'status' => 'error',
                'message' => 'Server error while uploading',
            ], 200);

        }

        return json_encode([
            'status' => 'success',
            'url' => $public_path.'/frontend/images/profile_photos/user_'.Auth::Id().'/' . $filename
        ], 200);

    }



}