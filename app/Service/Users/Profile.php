<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/8/2016
 * Time: 5:10 PM
 */
namespace App\Service\Users;
use App\Entity\user_profile as user_profile;
use Doctrine\ORM\EntityManager;
use DateTime;
use Illuminate\Support\Facades\Config;

Class Profile
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Profle constructor.
     *
     * @param EntityManager $em
     * @author hkaur5
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return profile photo path of user.
     * if photo does not exist in database then return
     * default profile photo path according to gender.
     * @param $user_id
     * @return string Profile photo path
     */
    public function getUserProfilePhoto($user_id)
    {
        $public_path = Config::get('constants.PUBLIC_PATH');
        $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');

        $usersRepo = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersRepo->getRowObject(['id',$user_id]);
        
        if($user_obj->getUserProfile())
        {
            if($user_obj->getUserProfile()->getProfile_photo_name())
            {
                $profile_photo_name = $user_obj->getUserProfile()->getProfile_photo_name();
                if(file_exists($server_public_path.'/frontend/images/profile_photos/user_'.$user_id.'/'.$profile_photo_name)){

                    return $public_path.'/frontend/images/profile_photos/user_'.$user_id.'/'.$profile_photo_name;
                }
                else
                {
                    if($user_obj->getGender() == $usersRepo::GENDER_MALE){
                        return $public_path.'/frontend/images/default_prof_photo_male.png';
                    }
                    else if($user_obj->getGender() == $usersRepo::GENDER_FEMALE){
                        return $public_path.'/frontend/images/default_prof_photo_female.png';
                    }
                    else{
                        return $public_path.'/frontend/images/default_prof_photo_male.png';
                    }
                }
            }
            else
            {
                if($user_obj->getGender() == $usersRepo::GENDER_MALE){
                    return $public_path.'/frontend/images/default_prof_photo_male.png';
                }
                else if($user_obj->getGender() == $usersRepo::GENDER_FEMALE){
                    return $public_path.'/frontend/images/default_prof_photo_female.png';
                }
                else{
                    return $public_path.'/frontend/images/default_prof_photo_male.png';
                }
            }
        }
        else{
            if($user_obj->getGender() == $usersRepo::GENDER_MALE){
                return $public_path.'/frontend/images/default_prof_photo_male.png';
            }
            else if($user_obj->getGender() == $usersRepo::GENDER_FEMALE){
                return $public_path.'/frontend/images/default_prof_photo_female.png';
            }
            else{
                return $public_path.'/frontend/images/default_prof_photo_male.png';
            }
        }


    }
}