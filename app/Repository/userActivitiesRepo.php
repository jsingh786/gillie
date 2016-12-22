<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/6/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\user_activities as user_activities;
use Doctrine\ORM\EntityManager;
use App\Repository\usersRepo as userRepo;
use App\Repository\activitiesRepo as activitiesRepo;

class userActivitiesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_activities';

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
     * get users activities
     */
     public function get($user_id, $fav = 0 )
     {
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('ua')
             ->from('App\Entity\user_activities','ua')
             ->where('ua.users = :user_obj');
         $q_1->setParameter('user_obj',$user_obj);
        $result = $q_1->getQuery()->getResult();
        return $result;

     }


    /**
     * Delete records on basis of paramters.
     * @param integer $user_id
     * @return boolean true
     */
    public function delete($user_id)
    {
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->delete('App\Entity\user_activities','ua')
            ->where('ua.users = :user_obj');
        $q_1->setParameter('user_obj',$user_obj);
        $result = $q_1->getQuery()->getResult();
        return $result;
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

        $usersEntity = new \App\Repository\usersRepo($this->em);
        $activitiesEntity = new \App\Repository\activitiesRepo($this->em);
        foreach ($params as $key=>$param )
        {
            $obj = new user_activities;
            $user_obj = $usersEntity->getRowObject(['id',$param['user_id']]);
            $activity_obj = $activitiesEntity->getRowObject(['id',$param['activity_id']]);
            $obj->setUsers($user_obj);
            $obj->setActivities($activity_obj);
            $this->em->persist($obj);

        }
        $this->em->flush();
        return $obj;
    }
}