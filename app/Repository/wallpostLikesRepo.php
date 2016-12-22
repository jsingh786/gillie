<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/15/2016
 * Time: 3:53 PM
 */



namespace App\Repository;
use App\Entity\wallpost_likes as wallpost_likes;
use Doctrine\ORM\EntityManager;


class wallpostLikesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\wallpost_likes';

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
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

    /**
     * Add a user like.
     * @author rawatabhishek
     * @param integer $params['userId'=>'123', 'wallpostId'=>'10']
     * @return integer id
     * @version 1.0
     */
    public function likeUser(array $params)
    {
        $likeObj = new wallpost_likes();
        $wallpostobj = new \App\Repository\wallpostRepo($this->em);
        $wallpostId = $wallpostobj->getRowObject(['id', $params['wallpostId']]);

        $wallpostLikesObj = new \App\Repository\usersRepo($this->em);
        $userId = $wallpostLikesObj->getRowObject(['id', $params['userId']]);
        $likeObj->setLikesuser($userId);
        $likeObj->setLikesWallpost($wallpostId);
        $this->em->persist($likeObj);
        $this->em->flush();
        if ($likeObj->getId()) {
            return $likeObj->getId();
        }
    }

    /**
     * Remove a user like.
     * @author rawatabhishek
     * @param integer $id(Row id of the record to be deleted)
     * @return boolean
     * @version 1.0
     */
    public function unlikeUser($id)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('App\Entity\wallpost_likes', 'f')
            ->where('f.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $query->execute();
        return true;
    }

    /**
     * To check whether user is liking a post or not.
     * @author rawatabhishek
     * @param integer $params['userId'=>'123', 'wallpostId'=>'10']
     * @return object
     * @version 1.0
     */
    public function isWallpostLikedByUser(array $params)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('l')
            ->from('App\Entity\wallpost_likes','l');
        $query->where('l.likesuser =:userId');
        $query->AndWhere('l.likesWallpost =:wallpostId');
        $query->setParameter('userId', $params['userId']);
        $query->setParameter('wallpostId', $params['wallpostId']);
        $result = $query->getQuery()->getResult();
        return $result;
    }

    /**
     * To get wallpost likes count.
     * @author rawatabhishek
     * @param integer $wallpostId(Wall post id)
     * @return object
     * @version 1.0
     */
    public function getLikesCount($wallpostId)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('l')
        ->from('App\Entity\wallpost_likes','l');
        $query->AndWhere('l.likesWallpost =:wallpostId');
        $query->setParameter('wallpostId', $wallpostId);
        $result = $query->getQuery()->getResult();
        return $result;

    }

}