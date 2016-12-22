<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 9/9/2016
 * Time: 2:42 PM
 */
namespace App\Repository;
use App\Entity\forum_comments;
use Doctrine\ORM\EntityManager;
use App\Service\Users\Profile as UserProfileService;



class forumCommentsRepo
{

    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    private $class = 'App\Entity\forum_comments';
    /**
     * @var EntityManager
     */
    private $em;


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
     * Add comment into database
     *
     * @author rkaur3
     * @param $comData[forum_id,comment,user_id]
     * @return array
     * @version 1.0
     * Dated 12-sep-2016
     */
    public function add($comData){

        $forumCmtObj = new forum_comments();
        $forumCmtObj->setMessage($comData['comment']);
        $forumObj = new \App\Repository\forumRepo($this->em);
        $forumCmtObj->setForum($forumObj->getRowObject(['id',$comData['forum_id']]));
        $forumCmtObj->setStatus(self::STATUS_ACTIVE);
        $userObj = new \App\Repository\usersRepo($this->em);
        $forumCmtObj->setUsers($userObj->getRowObject(['id',$comData['user_id']]));

        $this->em->persist($forumCmtObj);
        $this->em->flush();

        $userProfileService = new UserProfileService($this->em);
        return array('id'=>$forumCmtObj->getId(),
            'message'=>$forumCmtObj->getMessage(),
            'posted_date'=>$forumCmtObj->getCreatedAt()->format(\DateTime::ATOM),
            //'comment_by_user'=>$forumCmtObj->getUsers()->getFirstname()." ".$forumCmtObj->getUsers()->getLastname()
            'comment_by_user'=>'You',
            'comment_by_user_id'=>$comData['user_id'],
            'isMyComment'=>1,
            'user_profile_photo'=>$userProfileService->getUserProfilePhoto($forumCmtObj->getUsers()->getId())
        );
    }

    /**
     * Updates Comment.
     *
     * @param integer $id
     * @param string $text
     * @return mixed
     * @author jsingh7
     * @version 1.0
     */
    public function update($id, $text)
    {
        $qb = $this->em->createQueryBuilder();
        $q = $qb->update('App\Entity\forum_comments', 'fc')
            ->set('fc.message', '?2')
            ->where('fc.id = ?1')
            ->setParameter(1, $id)
            ->setParameter(2, $text)
            ->getQuery();
        return $q->execute();
    }

    /**
     * Get comments of particular forum
     *
     * @param integer $forumId
     * @param integer $limit
     * @param integer $offset
     * @return array
     * @author rkaur3
     * @version 1.1
     * Dated 10-oct-2016
     */
    
    public function get($forumId, $limit = null, $offset = null )
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('fc')
            ->from('App\Entity\forum_comments', 'fc')
             ->setParameter('1', $forumId)
             ->where('fc.forum=?1')
             ->andWhere('fc.status='.self::STATUS_ACTIVE)
             ->orderBy('fc.created_at','DESC');

        //List length
        if( $limit )
        {
            $q_1->setFirstResult( $offset );
            $q_1->setMaxResults( $limit );
        }

        $result =  $q_1->getQuery()->getResult();
        if($result)
        {
            $last_record = end($result);
            $q_2 = $qb->select('fcc')
                ->from('App\Entity\forum_comments','fcc')
                ->where('fcc.forum=?2')
                ->andWhere('fcc.id < ?1')
                ->andWhere('fcc.status='.self::STATUS_ACTIVE);
            $q_2->setParameter(2,$forumId);
            $q_2->setParameter(1,$last_record->getId());
            $is_more_records = $q_1->getQuery()->getResult();
            $data['forum_comments'] = $result;
            $data['is_more_records'] = count($is_more_records);
        }
        else
        {
            $data['forum_comments'] = '';
            $data['is_more_records'] = 0;
        }
        return $data;
    }

    /**
     * Get total count of comments of forum
     *
     * @param $forumId
     * @return integer count
     * @author rkaur3
     * @version 1.0
     * Dated 12-sep-2016
     */
    public function getCount( $forumId )
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('fc')
            ->from('App\Entity\forum_comments', 'fc')
            ->setParameter('1', $forumId)
            ->where('fc.forum=?1')
            ->andWhere('fc.status='.self::STATUS_ACTIVE);

        $comments =  $q_1->getQuery()->getResult();
        return count($comments);
    }

    /**
     * Removes Forum-comment.
     *
     * @param integer $id
     * @return Array[id, forumId] or false
     * @author jsingh7
     * @version 1.0
     */
    public function remove($id)
    {
        $forumCommentObj = $this->getRowObject(['id', $id]);
        if($forumCommentObj) {
            $this->em->remove($forumCommentObj);
            $this->em->flush();
            return ['id' => $id, 'forumId' =>$forumCommentObj->getForum()->getId()];
        }
        else
        {
            return false;
        }
    }
}