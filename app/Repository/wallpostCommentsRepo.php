<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/15/2016
 * Time: 3:51 PM
 */


namespace App\Repository;
use App\Entity\wallpost_comments as wallpost_comments;
use Doctrine\ORM\EntityManager;


class wallpostCommentsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\wallpost_comments';

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
     * Add a wall post comment.
     * @author rawatabhishek
     * @param integer $params['userId'=>'32', 'wallpostId'=>'10']
     * @param string $text(Comment text)
     * @return Object
     * @version 1.0
     */
    public function addComment(array $params, $text)
    {
        $likeObj = new wallpost_comments();
        $wallpostobj = new \App\Repository\wallpostRepo($this->em);
        $wallpostId = $wallpostobj->getRowObject(['id', $params['wallpostId']]);
        $wallpostLikesObj = new \App\Repository\usersRepo($this->em);
        $userId = $wallpostLikesObj->getRowObject(['id', $params['userId']]);

        $likeObj->setCommentsUser($userId);
        $likeObj->setCommentsWallpost($wallpostId);
        $likeObj->setText($text);
        $this->em->persist($likeObj);
        $this->em->flush();
        if($likeObj) {
            return $likeObj;
        }
    }


    /**
     * Remove a wallpost comment.
     * @author rawatabhishek
     * @param integer $id(Row id of the record to be deleted)
     * @return boolean
     * @version 1.0
     */
    public function removeComment($id)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('App\Entity\wallpost_comments', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        $query->execute();
        return true;
    }

    /**
     * Get wall posts comments.
     * @author rawatabhishek
     * @param integer $limitAndOffset['limit'=>'32', 'offset'=>'10']
     * @param integer $postId(wall post id)
     * @return Object
     * @version 1.0
     */
    public function getWallPostComment(array $limitAndOffset, $postId)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('c')
            ->from('App\Entity\wallpost_comments', 'c');
        $query->where('c.commentsWallpost =:postId');
        $query->setParameter('postId', $postId);
        $query->setMaxResults($limitAndOffset['limit']);
        $query->setFirstResult($limitAndOffset['offset']);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}