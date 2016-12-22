<?php
/**
 * Created by PhpStorm.
 * User: rawatabhishek
 * Date: 11/03/2016
 * Time: 6:01 PM
 */
namespace App\Repository;
use App\Entity\followw;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\Integer;

class followwRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\followw';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author rawatabhishek
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Adds a follower.
     * @author rawatabhishek
     * @param array $data ['followerId'=>'value', followedId =>'value']
     * @return Integer
     * @version 1.0
     */
    public function add($data)
    {
        if ($data['followerId'] && $data['followedId']) {
            $followerObj = new followw();
            $usersEntity = new \App\Repository\usersRepo($this->em);
            $follower = $usersEntity->getRowObject(['id', $data['followerId']]);
            $followed = $usersEntity->getRowObject(['id', $data['followedId']]);
            $followerObj->setFollowerUser($follower);
            $followerObj->setFollowedUser($followed);
            $this->em->persist($followerObj);
            $this->em->flush();
            return $followerObj->getId();
        }
    }

    /**
     * Removes a follower.
     * @param integer $id
     * @version 1.0
     * @return true
     */
    public function remove($id)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('App\Entity\followw', 'followw')
        ->where('followw.id = :id')
        ->setParameter('id', $id)
        ->getQuery();
        $query->execute();
        return true;
    }


    /**
     * Check whether the user is followed by other user or not.
     * @param integer $followerId(Id of the user who is following the other user)
     * @param integer followedId(Id of the user who is being followed)
     * @return Boolean
     * @author rawatabhishek
     * @version 1.0
     */
    public function isUser1followedByUser2($followerId, $followedId)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('followw')
            ->from('App\Entity\followw','followw');
        $query->where('followw.followerUser =:follower');
        $query->AndWhere('followw.followedUser =:followed');
        $query->setParameter('follower', $followerId);
        $query->setParameter('followed', $followedId);
        $result = $query->getQuery()->getResult();
        if($result){
            return true;
        }
        else{
            return false;
        }
    }


    /**
     * Get user(s) followed by a user.
     * @param integer $followerUserId
     * @param integer $offset
     * @param integer $limit
     * @return object
     * @author rawatabhishek
     * @version 1.0
     */
    public function followedUsers($followerUserId, array $limitAndOffset)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('followw')
            ->from('App\Entity\followw','followw');
        $query->where('followw.followerUser =:follower');
        $query->setParameter('follower', $followerUserId);

        if ($limitAndOffset) {
            $query->setMaxResults($limitAndOffset['limit']);
            $query->setFirstResult($limitAndOffset['offset']);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Get user followed by a user
     * @param integer $followerUserId
     * @return array
     * @param array $limitAndOffset
     * @author rawatabhishek
     * @version 1.0
     */
    public function followingUsers($followerUserId, $limitAndOffset)
    {
        $qb     = $this->em->createQueryBuilder();
        $query  = $qb->select('followw')
            ->from('App\Entity\followw','followw');
        $query->setMaxResults($limitAndOffset['limit']);
        $query->setFirstResult($limitAndOffset['offset']);
        $query->where('followw.followerUser =:follower');
        $query->setParameter('follower', $followerUserId);
        $result = $query->getQuery()->getResult();
        if($result)
        {
            $data['following'] = $result;
            $last_record = end($result);
            $qbd = $this->em->createQueryBuilder();
            $query1 = $qbd->select('followed')
            ->from('App\Entity\followw','followed');
            $query1->where('followed.id > ?1');
            $query1->setParameter(1, $last_record->getId());
            $query1->andWhere('followed.followerUser = ?2');
            $query1->setParameter(2, $followerUserId);
            $moreRecords = $query1->getQuery()->getResult();

            if($moreRecords)
            {
                $data['moreRecords'] = count($moreRecords);
            }

            else
            {
                $data['moreRecords'] = 0;
            }
            return $data;
        }

    }
    
    /**
     * Fetch followers matching the search input.
     * @author rawatabhishek
     * @param array $searchKeywords_arr (Input to be searched)
     * @param integer $follower_user_id
     * @param array $limitAndOffset
     * @return array
     * @version 1.0
     */
    public function searchFollowingUsers($searchKeywords_arr, $follower_user_id, $limitAndOffset)
    {

        $condition = "";
        $i = 0 ;
        $counter = count($searchKeywords_arr)-1;
        foreach ($searchKeywords_arr as $search) {

            $condition .= "followedUser.firstname LIKE '".$search."%'"." " ." OR followedUser.lastname LIKE '".$search."%'";
            if ($i != $counter) {
                $condition .= ' OR ';
            }
            $i++;
        }

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('followw')
        ->from('App\Entity\followw', 'followw');
        $query->leftJoin('followw.followedUser', 'followedUser');
        $query->where('followw.followerUser = ?1');
        $query->setParameter(1, $follower_user_id);
        $query->andWhere($condition);
        $query->setFirstResult($limitAndOffset['offset']);
        $query->setMaxResults($limitAndOffset['limit']);
        $data = $query->getQuery()->getResult(); //$data is an object of users matching the search criteria.

        if ($data) {
            $result['following'] = $data;
            $last_record = end($data);
            $qb = $this->em->createQueryBuilder();
            $query1 = $qb->select('followed')
                ->from('App\Entity\followw', 'followed');
            $query1->where('followed.id > ?1');
            $query1->setParameter(1, $last_record->getId());
            $query1->andWhere('followed.followerUser = ?2');
            $query1->setParameter(2, $follower_user_id);
            $moreRecords = $query1->getQuery()->getResult();

            if ($moreRecords) {
                $result['moreRecords'] = count($moreRecords);
            } else {
                $result['moreRecords'] = 0;
            }

            return $result;
        }
     }
    
   /**
 * Get local search users details
 * @param integer $followerUserId
 * @param integer $followedId
 * @return object
 * @author rawatabhishek
 * @version 1.0
 */
    public function localsFollowingUsers($followerUserId, $followedUserId)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('followw')
            ->from('App\Entity\followw', 'followw');
        $query->where('followw.followerUser =:follower');
        $query->setParameter('follower', $followerUserId);
        $query->andWhere('followw.followedUser =:followed');
        $query->setParameter('followed', $followedUserId);
        $result = $query->getQuery()->getResult();
        return $result;
    }

    /**
     * Get local search users details
     * @param integer $followerUserId
     * @param integer $followedId
     * @return object
     * @author rawatabhishek
     * @version 1.0
     */
    public function getlocalUsersRowobject($followerUserId, $followedUserId)
    {
//        echo $followedUserId; die;
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('followw')
            ->from('App\Entity\followw', 'followw');
        $query->where('followw.followerUser =:follower');
        $query->setParameter('follower', $followerUserId);
        $query->andWhere('followw.followedUser =:followed');
        $query->setParameter('followed', $followedUserId);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
