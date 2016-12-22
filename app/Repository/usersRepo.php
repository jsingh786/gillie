<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/14/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\users;
use Doctrine\ORM\EntityManager;
use Hash;


class usersRepo
{

    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;

    CONST GENDER_MALE = 1;
    CONST GENDER_FEMALE = 2;
    /**
     * @var string
     */
    private $class = 'App\Entity\users';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author jsingh7
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Returns object of the record on basis of column name - value pair.
     *
     * @param Array $columnNameValuePair ['id', 123]
     * @return null|object
     * @author jsingh7
     * @version 1.1
     *
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

   

    /**
     * todo This function should not be here. Nothing related to mapper class.
     * todo Please shift this OR simply use constants where required.
     * Get gender names
     *
     * @param integer $genderValue
     * @return string
     * @author rkaur3
     * @version 1.0
     */
    public function getGenderName($genderValue)
    {

        if($genderValue == self::GENDER_MALE)
        {
            return "Male";
        }
        else if($genderValue == self::GENDER_FEMALE)
        {

            return "Female";
        }
    }

    /**
     * todo This function should not be here. Nothing related to mapper class.
     * todo Please shift this OR simply use constants where required.
     * Get status values
     *
     * @params integer $statusValue
     * @author rkaur3
     * @version 1.0
     * Dated 4th July,2016
     */
    public  function getStatusName( $statusValue )
    {
        if($statusValue == self::STATUS_ACTIVE)
        {

            return "Active";
        }
        else if($statusValue == self::STATUS_INACTIVE)
        {

            return "Inactive";
        }
    }

    /**
     * Returns users as per parameters.
     *
     * @param array|null $limitNOffset ['limit'=>100, 'offset'=>0]
     * @param array|null $order ['orderColumn'=>'id', 'order'=>'desc']
     * @param array|null $filter ['columns'=>['id','name', 'email'], 'keywords'=>['abc', 'xyz', 'pqr']]
     * @version 1.0
     * @author jsingh7
     */
    public function get(array $limitNOffset = null,
                        array $order = null,
                        array $filter = null)
    {
        $qb     = $this->em->createQueryBuilder();
        $alias  = 'usrs';
        $query  = $qb->select($alias)
            ->from( $this->class, $alias );

        //List length
        if($limitNOffset) {
            $query->setFirstResult($limitNOffset['offset'])
                ->setMaxResults($limitNOffset['limit']);
        }

        //Sorting
        if($order) {
            $query->orderBy($alias.'.'.$order['column'], $order['order']);
        }

        //Filtering
        if($filter) {
            if($filter['columns'] && $filter['keywords'] && is_array($filter['columns']) && is_array($filter['keywords'])) {
                foreach ($filter['columns'] as $key_1 => $filter_column) {
                    foreach ($filter['keywords'] as $key_2 => $filter_keyword) {
                        $query->orWhere($filter_column . ' LIKE :word_' . $key_2)
                            ->setParameter('word_' . $key_2, '%' . $filter_keyword . '%');
                    }
                }
            }
        }

        return $query->getQuery()->getResult();
    }

    /**
     *
     * todo use get function of this class.
     * Returns Collection of users
     *
     * @param array-associative $params
     * @param string $searchParam
     * @param integer $start [default 0]
     * @param integer $length [default 1]
     * @return array [Array of user records]
     * @author rkaur3
     * @version 1.0
     */
    public function getAllUsers($params, $searchParam, $start, $length)
    {
         $qb = $this->em->createQueryBuilder();
         $q_1 = $qb->select('users')
            ->from('App\Entity\users','users');

            //Filtering
            if($searchParam)
            {
                $q_1->where('users.firstname LIKE :searchParam');
                $q_1->orWhere('users.lastname LIKE :searchParam');
                $q_1->orWhere('users.email LIKE :searchParam');
                $q_1->setParameter('searchParam', '%'.$searchParam.'%');
            }

            //List length
            if( $length )
            {
                $q_1->setFirstResult($start);
                $q_1->setMaxResults($length);
            }
        $column = 'updated_at';
        $columnDir = 'desc';
            //sort users acc to field name passed
            if (array_key_exists('order', $params) && !empty($params['order'][0]))
            {
                if (array_key_exists('column', $params['order'][0]))
                {
                    $columnIndex = $params['order'][0]['column'];
                }
                if (array_key_exists('dir', $params['order'][0]))
                {
                    $columnDir = $params['order'][0]['dir'];
                }
               /* echo $params['columns'][$columnIndex]['data'];
                die();*/
                if(!empty($column = $params['columns'][$columnIndex]['data']))
                {
                    $q_1->orderBy("users.".$column, $columnDir);
                }
            }
            return $q_1->getQuery()->getResult();
    }

    /**
     * Returns total number of users in database.
     *
     * @return integer count
     * @author rkaur3
     * @version 1.0
     * Dated 15June,2016
     *
     */
    //todo remove this function, instead use get function of this class.
    //todo If you want to use count function of PHP, do it in controller class.
    public function getUserCount()
    {
        $users =$this->em->getRepository($this->class)->findAll();
        return count($users);
    }


    /**
     * Saves user data to database.
     *
     * @param array $userData ['email'=>'xyz@abc.com']
     * @author jsingh7
     * @returns id
     * @version 1.0
     */
    public function save(array $userData)
    {
        $userObj = new users();
        $userObj->setEmail($userData['email']);
        $userObj->setFirstname($userData['firstname']);
        if(isset($userData['lastname']))        {$userObj->setLastname($userData['lastname']);}
        if(isset($userData['gender']))          {$userObj->setGender($userData['gender']);}
        if(isset($userData['zipcode']))         {$userObj->setZipcode($userData['zipcode']);}
        if(isset($userData['profile_image']))   {$userObj->setProfileImage($userData['profile_image']);}
        if(isset($userData['address']))         {$userObj->setAddress($userData['address']);}
        $userObj->setIsActive($userData['is_active']);
        $this->em->persist($userObj);
        $this->em->flush();
        return $userObj->getId();
    }

    /**
     * Saves user data to database.
     *
     * @param array $userData ['email'=>'xyz@abc.com']
     * @author jsingh7
     * @returns void
     * @version 1.0
     */
    public function update(array $userData)
    {
        $userObj = $this->getRowObject(['id'=>$userData['id']]);
        if(isset($userData['email']))           {$userObj->setEmail($userData['email']);}
        if(isset($userData['firstname']))       {$userObj->setFirstname($userData['firstname']);}
        if(isset($userData['lastname']))        {$userObj->setLastname($userData['lastname']);}
        if(isset($userData['gender']))          {$userObj->setGender($userData['gender']);}
        if(isset($userData['zipcode']))         {$userObj->setZipcode($userData['zipcode']);}
        if(isset($userData['profile_image']))   {$userObj->setProfileImage($userData['profile_image']);}
        if(isset($userData['address']))         {$userObj->setAddress($userData['address']);}
        if(isset($userData['is_active']))       {$userObj->setIsActive($userData['is_active']);}
        $this->em->persist($userObj);
        $this->em->flush();
    }


    /**
     * saves new user / edit user details
     *
     * @params associative array $userData
     * @return integer id
     * @author rkaur3
     * @version 1.1
     * Dated 29Aug,2016
     */
    // todo Please shift all the control statements to controller.
    // todo Please keep code simple, use save and update functiona of this class.
    public function saveUser($userData)
    {
        //dd($userData['gender']);
        //update user details
        if(isset($userData['user_id']))
        {

            $userObj = $this->getRowObject(['id',$userData['user_id']]);
            if(array_key_exists('firstname',$userData )) {
                $userObj->setFirstName($userData['firstname']);
            }
            if(array_key_exists('lastname',$userData )) {
                $userObj->setLastName($userData['lastname']);
            }
            if(array_key_exists('gender',$userData )) {
                $userObj->setGender($userData['gender']);
            }
            if(array_key_exists('address',$userData )) {
                $userObj->setAddress($userData['address']);
            }
            if(array_key_exists('zip_code',$userData )) {
                $userObj->setZipcode($userData['zip_code']);
            }
            if(array_key_exists('country_id',$userData)) {
                $countryRepo = new \App\Repository\countryRepo($this->em);
                $countryObj = $countryRepo->getRowObject(['id', $userData['country_id']]);
                $userObj->setCountry($countryObj);
            }
            if(array_key_exists('city_id',$userData)) {
                if ($userData['city_id'] != "") {
                    $cityRepo = new \App\Repository\cityRepo($this->em);
                    $cityObj = $cityRepo->getRowObject(['id', $userData['city_id']]);
                    $userObj->setCity($cityObj);
                }


            }
            if(array_key_exists('state_id',$userData)) {
                if ($userData['state_id'] != "") {
                    $stateRepo = new \App\Repository\stateRepo($this->em);
                    $stateObj = $stateRepo->getRowObject(['id', $userData['state_id']]);
                    $userObj->setState($stateObj);
                }
            }
            if(array_key_exists('longitude',$userData )) {
                $userObj->setLongitude($userData['longitude']);
            }
            if(array_key_exists('latitude',$userData )) {
                $userObj->setLatitude($userData['latitude']);
            }
            if(array_key_exists('address',$userData )) {
                $userObj->setAddress($userData['address']);
            }
            if(array_key_exists('is_active',$userData )) {
                $userObj->setIsActive($userData['is_active']);
            }
            $rolesObj = new \App\Repository\rolesRepo($this->em);
            $userObj->setUserRoles($rolesObj->getRowObject(2));
            $this->em->persist($userObj);
            $this->em->flush();

            return $userObj->getId();
        }
        //add user details
        else
        {
            //disable softdelete, to update user details who registers again with similar email address
            $this->em->getFilters()->disable('soft-deleteable');
            $qb = $this->em->createQueryBuilder();
            $qb->update('App\Entity\users', 'u')
                ->set('u.deleted_at', '?2')
                ->set('u.firstname','?3')
                ->set('u.lastname','?4')
                ->set('u.password','?5')
                ->where('u.email=?1')
                ->andWhere('u.deleted_at IS NOT NULL');

                $qb->setParameter(1, $userData['email']);
                $qb ->setParameter(2, NULL);
                $qb->setParameter(3, $userData['firstname']);
                $qb->setParameter(4, $userData['lastname']);
                $qb->setParameter(5, Hash::make($userData['password']));
                if(isset($userData['address'])){
                    $qb ->set('u.address','?6');
                    $qb ->setParameter(6, $userData['address']);
                }

            if(array_key_exists('zipcode',$userData )) {
                $qb ->set('u.zipcode','?7');
                $qb ->setParameter(7, $userData['zipcode']);
            }


            if(array_key_exists('city_id',$userData )) {
                $qb ->set('u.city','?9');
                $cityRepo = new \App\Repository\cityRepo($this->em);
                $cityObj = $cityRepo->getRowObject(['id', $userData['city_id']]);
                $qb ->setParameter(9, $cityObj);
            }


            if(array_key_exists('state_id',$userData )) {
                $qb ->set('u.state','?10');
                $stateRepo = new \App\Repository\stateRepo($this->em);
                $stateObj = $stateRepo->getRowObject(['id', $userData['state_id']]);
                $qb ->setParameter(10, $stateObj);
            }

            if(array_key_exists('longitude',$userData )) {
                $qb ->set('u.longitude','?11');
                $qb ->setParameter(11, $userData['longitude']);
            }

            if(array_key_exists('latitude',$userData )) {
                $qb ->set('u.latitude','?12');
                $qb ->setParameter(12, $userData['latitude']);
            }
               /* $qb->setParameter(7, $userData['zipcode']);*/
            if(isset($userData['is_active'])){
                    $qb ->set('u.is_active','?8');
                    $qb->setParameter(8, $userData['is_active']);
                }

            $query = $qb ->getQuery();
            $p = $query->execute();
            $this->em->getFilters()->enable('soft-deleteable');
            //check if new user is added i.e update query returns 0 ,then add new user into database
             if($p == 0)
             {
                $newUser = new users; 
                $newUser->setFirstName($userData['firstname']);
                $newUser->setLastName($userData['lastname']);
                 if(isset($userData['gender'])) {
                     $newUser->setGender($userData['gender']);
                 }
                $newUser->setEmail($userData['email']);
                $newUser->setPassword(Hash::make($userData['password']));
                 if(isset($userData['address'])) {
                     $newUser->setAddress($userData['address']);
                 }

                 if(array_key_exists('country_id',$userData)) {
                     $countryRepo = new \App\Repository\countryRepo($this->em);
                     $countryObj = $countryRepo->getRowObject(['id', $userData['country_id']]);
                     $newUser->setCountry($countryObj);
                 }
                 
                 if(array_key_exists('city_id',$userData )) {

                     $cityRepo = new \App\Repository\cityRepo($this->em);
                     $cityObj = $cityRepo->getRowObject(['id', $userData['city_id']]);
                     $newUser->setCity($cityObj);

                 }
                 if(array_key_exists('state_id',$userData )) {
                     $stateRepo = new \App\Repository\stateRepo($this->em);
                     $stateObj = $stateRepo->getRowObject(['id', $userData['state_id']]);
                     $newUser->setState($stateObj);
                 }
                 if(array_key_exists('longitude',$userData )) {
                     $newUser->setLongitude($userData['longitude']);
                 }
                 if(array_key_exists('latitude',$userData )) {
                     $newUser->setLatitude($userData['latitude']);
                 }
                 if(array_key_exists('zipcode',$userData )) {
                     $newUser->setZipcode($userData['zipcode']);
                 }
                 if(isset($userData['is_active'])) {
                     $newUser->setIsActive($userData['is_active']);
                 }
                 $rolesObj = new \App\Repository\rolesRepo($this->em);
                 $newUser->setUserRoles($rolesObj->getRowObject(2));//todo What is 2? Please use constant.
                
                $this->em->persist($newUser);
                $this->em->flush();

                return $newUser->getId();
            }
            else
            {
                return true;
            }
        }
    }

    /**
     * Deletes record of user from database.
     * @param $user_id
     * @return bool
     * @version 1.0
     */
    public function delete($user_id)
    {
        $user_obj = $this->getRowObject(['id',$user_id]);
        if($user_obj)
        {
            $this->em->remove($user_obj);
            $this->em->flush();
            return true;
        }
    }

    /**
     * SoftDelete User
     *
     * @param integer $user_id
     * @return bool 
     * @author rkaur3
     * @version 1.0
     * Dated 20june,2016
     */
    public function deleteUser($user_id)
    {
        $user_obj = $this->getRowObject(['id',$user_id]);
        if(count($user_obj) > 0)
        {
            $this->em->remove($user_obj);
            $this->em->flush();
            return true;
        }
    }

    public function changePassword($password,$id)
    {

        $qb = $this->em->createQueryBuilder();
        $query=$qb->update($this->class,'a')
            ->set('a.password', '?1')
            ->where('a.id = ?2')
            ->setParameter(1, $password)
            ->setParameter(2, $id)
            ->getQuery();
        $result = $query->execute();
        return $result;
    }


}