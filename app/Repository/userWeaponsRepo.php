<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/6/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\user_weapons;
use Doctrine\ORM\EntityManager;
use App\Repository\usersRepo as userRepo;
class userWeaponsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_weapons';

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
     * Get user's weapon
     * @param integer $user_id
     * @param boolean $favorite [optional] if true return records where favorite is 1.
     * @return array of objects
     * //Todo: Can't we make it like passing array type parameter ['id'=>123, 'favourite'=>true]
     */
    public function get($user_id, $favorite = false )
    {
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('uw')
            ->from('App\Entity\user_weapons','uw')
            ->where('uw.users = :user_obj');
        if($favorite)
        {
            $q_1->andWhere('uw.favourite = 1');
        }
        $q_1->setParameter('user_obj',$user_obj);
        $result = $q_1->getQuery()->getResult();
        return $result;
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
        $q_1 = $qb->delete('App\Entity\user_weapons','uw')
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
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $weaponsEntity = new \App\Repository\weaponsRepo($this->em);
        foreach ($params as $key=>$param )
        {
            $obj = new user_weapons;
            $user_obj = $usersEntity->getRowObject(['id',$param['user_id']]);
            $weapon_obj = $weaponsEntity->getRowObject(['id',$param['weapon_id']]);

            $obj->setusers($user_obj);
            $obj->setWeapons($weapon_obj);
            $obj->setFavourite($param['favorite']);
            $this->em->persist($obj);

        }

        $this->em->flush();
        return $obj;
    }
}