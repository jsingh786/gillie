<?php

namespace App\Service\Elastic;
use App\Service\Elastic\Constants as Constants;
use App\Service\Elastic\Main as main;

use App\Repository\userHuntingLandRepo as userHuntingLandRepo;
use App\Repository\userWeaponsRepo as userWeaponsRepo;
use App\Repository\userActivitiesRepo as userActivitiesRepo;
use App\Repository\userSpeciesRepo as userSpeciesRepo;
use App\Repository\userPropertiesRepo as userPropertiesRepo;


class Users
{
    public function __construct( main $main,
                                 userHuntingLandRepo $userHuntingLandRepo,
                                 userWeaponsRepo $userWeaponsRepo,
                                 userActivitiesRepo $userActivitiesRepo,
                                 userSpeciesRepo $userSpeciesRepo,
                                 userPropertiesRepo $userPropertiesRepo

)
    {
        $this->main = $main;
        $this->userHuntingLandRepo = $userHuntingLandRepo;
        $this->userWeaponsRepo = $userWeaponsRepo;
        $this->userActivitiesRepo = $userActivitiesRepo;
        $this->userSpeciesRepo = $userSpeciesRepo;
        $this->userPropertiesRepo = $userPropertiesRepo;

    }

    /**
     * Add or update user details in elastic database.
     * @author hkaur5
     * @param object $userDetails
     * @param bool $alreadyExisting
     *
     * @return void
     */
    public function createUser($userDetails)
    {

        $userArr = array();

        $userArr['index']   = Constants::INDEX;
        $userArr['type']    = Constants::DOC_TYPE_USERS;
        $userArr['id']      = $userDetails->getId();


            $user_weapons = $this->userWeaponsRepo->get($userDetails->getId());

            //Get Weapons.
            if ($user_weapons) {
                foreach ($user_weapons as $key => $user_weapon) {
                    $weapons[$key]['id'] = $user_weapon->getWeapons()->getId();
                    $weapons[$key]['name'] = $user_weapon->getWeapons()->getName();
                }
            }else{
                $weapons = [];
            }


            //Get activities
            $user_activities = $this->userActivitiesRepo->get($userDetails->getId());
            if ($user_activities) {
                foreach ($user_activities as $key => $user_activity) {
                    $activities[$key]['id'] = $user_activity->getActivities()->getId();
                    $activities[$key]['name'] = $user_activity->getActivities()->getName();
                }
            }else{
                $activities = [];
            }

            //Get Species.
            $user_species = $this->userSpeciesRepo->get($userDetails->getId());

            if ($user_species) {

                foreach ($user_species as $key => $us_species) {

                    $species[$key]['id'] = $us_species->getSpecies()->getId();
                    $species[$key]['name'] = $us_species->getSpecies()->getName();

                }
            }else{
                $species = [];
            }


            //Get Properties.
            $user_properties = $this->userPropertiesRepo->get($userDetails->getId());
            if ($user_properties) {
                foreach ($user_properties as $key => $user_property) {
                    $properties[$key]['id'] = $user_property->getProperties()->getId();
                    $properties[$key]['name'] = $user_property->getProperties()->getName();

                }
            }else{
                $properties = [];
            }

            $favorite_weapon_obj = $this->userWeaponsRepo->get($userDetails->getId(), true);

            if ($favorite_weapon_obj) {
                $favorite_weapon_id = $favorite_weapon_obj[0]->getWeapons()->getId();
            } else {
                $favorite_weapon_id = '';
            }


            $favorite_hunting_land_obj = $this->userHuntingLandRepo->get($userDetails->getId(), true);
            if ($favorite_hunting_land_obj) {
                $favorite_hunting_land_id = $favorite_hunting_land_obj[0]->getHuntingLand()->getId();
            } else {
                $favorite_hunting_land_id = '';
            }

        //If user has not created profile.
        if($userDetails->getUserProfile()){
           $work           = $userDetails->getUserProfile()->getWork();
           $occupation     = $userDetails->getUserProfile()->getOccupation();
           $phone_number   = $userDetails->getUserProfile()->getPhoneNumber();
           $marital_status = $userDetails->getUserProfile()->getMarital_status();
           $school         = $userDetails->getUserProfile()->getSchool();
        }
        else{
            $work           = '';
            $occupation     = '';
            $phone_number   = '';
            $marital_status = '';
            $school         = '';
        }

        if($userDetails->getCity()){

            $city_id = $userDetails->getCity()->getId();
        }else{
            $city_id = null;
        }
        if($userDetails->getState()){

            $state_id = $userDetails->getState()->getId();
        }
        else{

            $state_id = null;
        }



        $doc = ['firstname'        => $userDetails->getFirstname(),
                'lastname'         => $userDetails->getLastname(),
                'gender'           => $userDetails->getGender(),
                'address'          => $userDetails->getAddress(),
                'zipcode'          => $userDetails->getZipcode(),
                'email'            => $userDetails->getEmail(),
                'work'             => $work,//profile
                'occupation'       => $occupation,
                'phone'            => $phone_number,
                'marital_status'   => $marital_status,
                'school'           => $school,//profile ends.
                'fav_hunting_land' => $favorite_hunting_land_id,
                'fav_weapon'       => $favorite_weapon_id,
                'activities'       => $activities,
                'weapons'          => $weapons,
                'species'          => $species,
                'properties'       => $properties,
                'city'             => $city_id,
                'state'            => $state_id,
                ];


        $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_USERS,
            'body' => [
                "query"=>  [
                    "bool"=> [
                        "must"=>   [ "match"=> [ "id"=>$userDetails->getId() ]],
                    ]
                ]
            ]
        ];

        //Checl if user document/record already exist.
       $response = $this->main->client->search($params);

        //if user already exist then update.
        if ($response['hits']['total']) {
            $userArr['body'] = ['doc' => $doc];

            $this->main->client->update($userArr);//updates.
        } else { 
            $userArr['body'] = $doc;

            $this->main->client->index($userArr); //Adds.
        }
    }

    
    public function getResponse()
    {

       /* $get_response_arr       = array();
        $get_response_arr['index']   = Constants::INDEX;
        $get_response_arr['type']    = Constants::DOC_TYPE_USERS;
        $get_response_arr['id']      = $userDetails->getId();

        $response =  $this->main->client->get($get_response_arr);
        return $response;*/
        /*$params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_USERS,
            'id' =>'AViGp5EVwZZaO7go9C4Y'
        ];*/

       // return $this->main->client->delete($params);

          $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_USERS,
            'body' => ['query' => ['match_all' => []], 'from' => 0, 'size' => 1000
            ]
        ];
        $response = $this->main->client->search($params);
        return $response;

    }


    public function search($params)
    {


        $must_query = array();
        if($params['properties'])
        {
            $must_query[] = ["terms"=> [ "properties.id"=>$params['properties'] ]];
        }

        if($params['activities']){

            $must_query[] = ["terms"=> [ "activities.id"=>$params['activities'] ]];
        }

        if($params['species'])
        {
            $must_query[] = ["terms"=> [ "species.id"=>$params['species'] ]];
        }

        if($params['weapons']){
            $must_query[] = ["terms"=> [ "weapons.id"=>$params['weapons'] ]];
        }

        if($params['city']){

            $must_query[] = ["match"=> [ "city"=>$params['city'] ]];
        }
        if($params['state']){
            $must_query[] = ["match"=> [ "state"=>$params['state'] ]];
        }

        $should_query = array();

        $should_query['should'] = [
//                       /* "must_not"=>[ "match"=> [ "title"=> "lazy"  ]],*/

                    [ "match"=> [ "firstname"=> $params['search_text'] ]],
                    [ "match"=> [ "lastname"=> $params['search_text'] ]],
                    [ "match"=> [ "address"=> $params['search_text'] ]],
                    [ "match"=> [ "properties.name"=> $params['search_text'] ]],
                    [ "match"=> [ "activities.name"=> $params['search_text'] ]],
                    [ "match"=> [ "species.name"=> $params['search_text'] ]],
                    [ "match"=> [ "weapons.name"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "firstname"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "lastname"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "address"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "properties.name"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "weapons.name"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "activities.name"=> $params['search_text'] ]],
                    [ "match_phrase_prefix"=> [ "species.name"=> $params['search_text'] ]]
        ];

        if($must_query && $should_query)
        {
            $must_should_array = array_merge( $should_query, ['must'=>$must_query]);
        }
        else if($should_query)
        {
            $must_should_array = $should_query;
        }
        else
        {
            $must_should_array = $should_query;
        }

        $query = ['bool'=>$must_should_array];



        $final_query = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_USERS,
            'body' => [
                'from' => $params['from'],
                'size' => $params['size'],
                "query"=> $query
                        ]
                ];


       /* echo "<pre>";
        print_r(json_encode($final_query));
        die;*/

        $response = $this->main->client->search($final_query);

        return array("users" => $response['hits']['hits'], "total_count" => $response['hits']['total']);
    }

}