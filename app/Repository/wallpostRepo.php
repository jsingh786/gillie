<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/15/2016
 * Time: 3:36 PM
 */

namespace App\Repository;
use App\Entity\wallpost as wallpost;
use Doctrine\ORM\EntityManager;


class wallpostRepo
{

    const WALLPOST_TYPE_TEXT = 1;
    const WALLPOST_TYPE_PHOTOS = 2;
    const WALLPOST_TYPE_VIDEO = 3;
    /**
     * @var string
     */
    private $class = 'App\Entity\wallpost';

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
     * @param array $columnNameValuePair [ column name and its value. ]
     * @return null|object
     * @author hkaur5
     * @version 1.0
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

    /**
     * Add wallpost to database.
     * @param array $params //Todo: Please define array.
     * @author hkaur5
     * @version 1.0
     * @return object of wallpost
     */
    public function add($params)
    {
        //Todo: Change variable name from $usersEntity to $usersRepo.
        $usersEntity = new \App\Repository\usersRepo($this->em);

        //Todo: Change variable name from $obj to $objEntity.
        $obj = new wallpost();
        //Todo: Change variable name from $user_obj to $userObj.
        $user_obj = $usersEntity->getRowObject(['id',$params['user_id']]);
        $obj->setWallpostUser($user_obj);
        $obj->setText($params['wallpost_text']);
        $obj->setType($params['wallpost_type']);
        $this->em->persist($obj);
        $this->em->flush();
        return $obj;
    }

    /**
     * Get wall posts
     * @param array $params //Todo: wrong documentation.
     * @author hkaur5
     * @return array of object
     * @version 1.0
     */
    //Todo: Why we are pasiing userIds to this function. We just need to pass $userId (The person who is logged in.)
    public function getWallposts(array $userIds, array $limitAndOffset)//Todo: Chnage method name to get().
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('w')
        ->from('App\Entity\wallpost', 'w');
        $query->where('w.wallpostUser IN (:UserIdArray)');
        $query->setParameter('UserIdArray', $userIds);
        $query->setMaxResults($limitAndOffset['limit']);
        $query->setFirstResult($limitAndOffset['offset']);
        $result = $query->getQuery()->getResult(); //Todo: Why can't we return $query->getQuery()->getResult() directly.
        return $result;
    }

}