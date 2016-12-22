<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 3:17 PM
 */
namespace App\Repository;
use App\Entity\user_profile as user_profile;
use Doctrine\ORM\EntityManager;
use DateTime;
use Illuminate\Support\Facades\Config;
use Profile;

class userProfileRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_profile';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * userProfileRepo constructor.
     *
     * @param EntityManager $em
     * @author hkaur5
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return object of the row.
     *
     * @param array $columnNameValuePair [ column name and its value. ]
     * @return null|object
     * @author hkaur5
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

    /**
     * Add record.
     * @param $params (Array containing user profile info) //Elaborate array.
     * @return user_profile
     * @author ?????
     * @version 1.0
     */
    public function add($params)
    {
        $usersRepo = new \App\Repository\usersRepo($this->em);
        //Todo: change $user_obj to $userObj.
        $user_obj = $usersRepo->getRowObject(['id',$params['user_id']]);
        $obj = new user_profile; //Todo: Change $obj to $userProfile.

        if(array_key_exists('phone',$params )) {
        $obj->setPhoneNumber($params['phone']);
        }
        if(array_key_exists('work',$params )) {
            $obj->setWork($params['work']);
        }
        if(array_key_exists('school',$params )) {
            $obj->setSchool($params['school']);
        }
        if(array_key_exists('occupation',$params )) {
            $obj->setOccupation($params['occupation']);
        }
        if(array_key_exists('user_id',$params )) { //Todo:  This is must value, we do not need to check array_key_exists on this.
            $obj->setUsers($user_obj);
        }
        if(array_key_exists('marital_status',$params )) {
            $obj->setMarital_status($params['marital_status']);
        }
        if(array_key_exists('profile_photo_name',$params )) {
            $obj->setProfile_photo_name($params['profile_photo_name']);
        }
        if(array_key_exists('dob',$params )) {
            if($params['dob']){
                $date = \DateTime::createFromFormat("M d, Y", $params['dob']);
                $obj->setDob($date );
            }
            else {$obj->setDob(null);}
        }
        $this->em->persist($obj);
        $this->em->flush();
        return $obj;
    }

    /**
     * Updates record.
     * Date of birth must be in 'M d, Y' format.
     * @param array $params ['work'=>'abc', 'school'=>'xyz'),
     * @return user_profile object or False.
     * @author ?????
     * @version 1.0
     */
    public function update($params)
    {
      
        $obj = self::getRowObject(['users',$params['user_id']]);
        if($obj) {
            if (array_key_exists('phone', $params)) {
                $obj->setPhoneNumber($params['phone']);
            }
            if (array_key_exists('work', $params)) {
                $obj->setWork($params['work']);
            }
            if (array_key_exists('school', $params)) {
                $obj->setSchool($params['school']);
            }
            if (array_key_exists('college', $params)) {
                $obj->setCollege($params['college']);
            }
            if (array_key_exists('occupation', $params)) {
                $obj->setOccupation($params['occupation']);
            }
            if(array_key_exists('marital_status',$params )) {
                $obj->setMarital_status($params['marital_status']);
            }
            if(array_key_exists('profile_photo_name',$params )) {
                $obj->setProfile_photo_name($params['profile_photo_name']);
            }

            if(array_key_exists('dob',$params )) {
                if($params['dob']){
                    $date = \DateTime::createFromFormat("M d, Y", $params['dob']);
                    $obj->setDob($date );
                }
                else {$obj->setDob(null);}
            }
            $this->em->persist($obj);
            $this->em->flush();
            return $obj;
        }
        else{
            return False;}
    }



}