<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/6/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\user_species;
use Doctrine\ORM\EntityManager;
use App\Repository\usersRepo as userRepo;
class userSpeciesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_species';

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
     * get users Species
     */
    public function get($user_id)
    {
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('us')
            ->from('App\Entity\user_species','us')
            ->where('us.users = :user_obj');
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
        $q_1 = $qb->delete('App\Entity\user_species','us')
            ->where('us.users = :user_obj');
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
        $speciesEntity = new \App\Repository\speciesRepo($this->em);
        foreach ($params as $key=>$param )
        {
            $obj = new user_species();
            $user_obj = $usersEntity->getRowObject(['id',$param['user_id']]);
            $species_obj = $speciesEntity->getRowObject(['id',$param['species_id']]);

            $obj->setusers($user_obj);
            $obj->setSpecies($species_obj);
            $this->em->persist($obj);

        }

        $this->em->flush();
        return $obj;
    }

    
}