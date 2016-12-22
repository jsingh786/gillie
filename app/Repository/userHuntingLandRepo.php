<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/6/2016
 * Time: 11:54 AM
 */

namespace App\Repository;
use App\Entity\user_hunting_land;
use Doctrine\ORM\EntityManager;
use App\Repository\usersRepo as userRepo;
class userHuntingLandRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_hunting_land';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * userActivitiesRepo constructor.
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
     * @param $id
     * @return null|object
     * @author hkaur5
     */
    public function getRowObject($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }



    /**
     * Returns hunting lands.
     *
     * @param integer $user_id [To get hunting land of specific users.]
     * @param Boolean $favourite [optional] [if true returns only favourite hunting lands]
     *
     * @returns  Array of objects
     * @author jsingh7
     * @version 1.0
     */
    public function get($user_id, $favourite = false)
    {

        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id', $user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('uhl')
            ->from('App\Entity\user_hunting_land', 'uhl')
            ->where('uhl.users = :user_obj');
        if($favourite)
        {
            $q_1->andWhere('uhl.favourite = 1');
        }
        $q_1->setParameter('user_obj', $user_obj);
        return $q_1->getQuery()->getResult();
    }

    /**
     * Delete records on basis of paramters.
     * @param integer $user_id
     * @param boolean $favorite [optional] if true delete records where favorite is 1.
     * @return boolean true
     */
    public function delete($user_id, $favorite = false)
    {
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->delete('App\Entity\user_hunting_land','uw')
            ->where('uw.users = :user_obj');
        if($favorite)
        {
            $q_1->andWhere('uw.favourite = 1');
        }
        $q_1->setParameter('user_obj',$user_obj);
        $result = $q_1->getQuery()->getResult();
        return true;
    }

    /**
     * Add Records
     * @param array $params
     * @author hkaur5
     * @return array of object
     */
    public function add($params)
    {
   //     dd($params);
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $hunting_landEntity = new \App\Repository\huntingLandRepo($this->em);
        foreach ($params as $key=>$param )
        {
            $obj = new user_hunting_land;
            $user_obj = $usersEntity->getRowObject(['id',$param['user_id']]);
            $hunting_land_obj = $hunting_landEntity->getRowObject(['id',$param['hunting_land_id']]);
            $obj->setusers($user_obj);
            $obj->setHuntingLand($hunting_land_obj);
            $obj->setFavourite($param['favorite']);
            $this->em->persist($obj);

        }

        $this->em->flush();
        return $obj;
    }
}
