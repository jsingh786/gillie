<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/6/2016
 * Time: 12:03 PM
 */
namespace App\Repository;
use App\Entity\user_properties;
use Doctrine\ORM\EntityManager;
use App\Repository\usersRepo as userRepo;
class userPropertiesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_properties';

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
     * get user's weapon
     */
    public function get($user_id, $fav = 0)
    {
        $usersRepo = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersRepo->getRowObject(['id', $user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('up')
            ->from('App\Entity\user_properties', 'up')
            ->where('up.users = :user_obj');
        $q_1->setParameter('user_obj', $user_obj);
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
        $usersRepo = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersRepo->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->delete('App\Entity\user_properties', 'up')
            ->where('up.users = :user_obj');
        $q_1->setParameter('user_obj', $user_obj);
        $result = $q_1->getQuery()->getResult();
        return true;
    }


    /**
     * Add Records
     * @param array $params ( array of array )
     * @author hkaur5
     * @return array of object
     */
    public function add($params)
    {

        $usersRepo = new \App\Repository\usersRepo($this->em);
        $propertiesRepo = new \App\Repository\propertiesRepo($this->em);
        foreach ($params as $key=>$param )
        {
            $obj = new user_properties();
            $user_obj = $usersRepo->getRowObject(['id',$param['user_id']]);
            $property_obj = $propertiesRepo->getRowObject(['id',$param['property_id']]);

            $obj->setusers($user_obj);
            $obj->setProperties($property_obj);
            $this->em->persist($obj);

        }

        $this->em->flush();
        return $obj;
    }

  
}
