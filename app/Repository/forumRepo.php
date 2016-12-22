<?php

namespace App\Repository;

use App\Entity\forum;
use Doctrine\ORM\EntityManager;



class forumRepo{

    CONST ACTIVE = 1;
    CONST DE_ACTIVE = 0;

    /**
     * @var string
     */
    private $class = 'App\Entity\forum';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * forumRepo constructor.
     *
     * @param EntityManager $em
     * @author jsingh7
     */
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
     * Saves new forum  into database
     *
     * @param array $forumData
     * @return integer type id
     * @author rkaur3
     * @version 1.0
     */
    public function add($forumData)
    {
        //Creating Objects.
        $forumObj = new forum;
        $catObj = new \App\Repository\forumCategoriesRepo($this->em);
        $userObj = new \App\Repository\usersRepo($this->em);

        //Setting data for forum.
        $forumObj->setTitle($forumData['title']);
        $forumObj->setForumCategories($catObj->getRowObject(['id', $forumData['selected_category']]));
        if(array_key_exists('description_formatted',$forumData)){$forumObj->setDescription($forumData['description_formatted']);};
        $forumObj->setStatus(self::ACTIVE);
        $forumObj->setUsers($userObj->getRowObject(['id', $forumData['user_id']]));
        $forumObj->setNoOfViews(0);
        $forumObj->setActivityAt(new \DateTime());

        $this->em->persist($forumObj);
        $this->em->flush();

        return $forumObj->getId();
    }

    /**
     * Get all active forums
     *
     * @return array
     * @author rkaur3
     * @version 1.1
     * Dated 5-sep-2016
     */
    public function get(array $params, $offset = null, $limit = null)
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('forum')
            ->from('App\Entity\forum', 'forum')
            ->where('forum.status='.self::ACTIVE);

        //List length
        if( $limit )
        {
            $q_1->setFirstResult( $offset );
             $q_1->setMaxResults( $limit );
        }
            $this->setFilters( $q_1, $params );
            $this->setOrderBy($q_1,$params);

        return $q_1->getQuery()->getResult();
    }

    /**
     * Common function to set filters for forums
     *
     * @param $q_1 query builder
     * @param array $params
     * @author rkaur3
     * @version 1.1
     * Dated 9-sep-2016
     */
    public function setFilters( $q_1,  array $params = [] ){

        //get forums acc to category id filter
        if(array_key_exists('by_cat_id', $params) && $params['by_cat_id']) {
            $q_1->setParameter('1', $params['by_cat_id']);
            $q_1->where('forum.forumCategories=?1');
        }

        //get forum detail,acc to id filter passed
        if(array_key_exists('by_forum_id', $params) && $params['by_forum_id']) {
            $q_1->setParameter('2', $params['by_forum_id']);
            $q_1->AndWhere('forum.id=?2');
        }

    }

    /**
     * Common function to set order for forums
     *
     * @param $q_1
     * @param array $params
     * @author rkaur3
     * @version 1.0
     * Dated 2-sep-2016
     */
    public function setOrderBy( $q_1, array $params = [] ){

        //get latest forums acc to order field and direction passed
        if(array_key_exists('by_order', $params) && !empty($params['by_order'])) {
            $q_1->orderBy('forum.'.$params['by_order'], $params['by_order_direction']);
        }

        //set order of forums i.e most trending
        if(array_key_exists('by_trending', $params) && !empty($params['by_trending'])) {
            $q_1->orderBy('forum.'.$params['by_trending'], $params['by_trending_direction']);
        }
        ////get latest comments of forum acc to id
       /* if(array_key_exists('by_forum_id', $params) && $params['by_forum_id']) {
            $q_1->orderBy('forum_comments.created_at', 'DESC');
        }*/
    }

    /**
     * Get count of active forums
     *
     * @param array $params
     * @return integer type count of forums
     * @author kaur3
     * @version 1.0
     * Dated 5-sep-2016
     */
    public function getForumsCount($params)
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('forum')
            ->from('App\Entity\forum', 'forum')
            ->where('forum.status='.self::ACTIVE);

        $this->setFilters( $q_1, $params );
        $this->setOrderBy($q_1,$params);

        $forums =  $q_1->getQuery()->getResult();
        return count($forums);
    }

    /**
     * Update Forum.
     *
     * @param array $forumData [id, title, description, category, status, activity_at, views, trending]
     * @author rkaur3
     * @author jsingh7 [Extended the function to receive all the data of forum]
     * @version 1.1
     *
     */
    public function update(array $forumData)
    {
        $forumObj = $this->getRowObject(['id', $forumData['id']]);
        if (isset($forumData['title'])) {
            $forumObj->setTitle($forumData['title']);
        }
        if (isset($forumData['description'])) {
            $forumObj->setDescription($forumData['description']);
        }
        if (isset($forumData['status'])) {
            $forumObj->setStatus($forumData['status']);
        }
        if (isset($forumData['category'])) {
            $categoryObj = new \App\Repository\forumCategoriesRepo($this->em);
            $forumObj->setforumCategories($categoryObj->getRowObject(['id', $forumData['category']]));
        }
        if (isset($forumData['activity_at'])) {
            $forumObj->setActivityAt($forumData['activity_at']);
        }
        if (isset($forumData['views'])) {
            $forumObj->setNoOfViews($forumData['views']);
        }
        if (isset($forumData['trending'])) {
            $forumObj->setTrending($forumData['trending']);
        }

        $this->em->persist($forumObj);
        $this->em->flush();
        return $forumObj->getId();
    }

    /**
     * Removes Forum.
     *
     * @param integer $id
     * @return true or exception
     * @author jsingh7
     * @version 1.0
     */
    public function remove($id)
    {
        $forumObj = $this->getRowObject(['id', $id]);
        $this->em->remove($forumObj);
        $this->em->flush();
        return true;
    }

    /**
     * ??????/
     *
     * @param $forumId
     * @return float
     * @author rkaur3
     */
    public function getAvgRating($forumId)
    {
        $forumObj = $this->getRowObject(['id', $forumId]);
        if (count($forumObj->getForumRating()) > 0)
        {
            $forumDetailArr = array();
            $totalStars = 0;
            $numIterations = 0;
            foreach($forumObj->getForumRating() as $fr)
            {
                $rating = $fr->getRatingStars();
                $totalStars = $totalStars + $rating;
                $numIterations++;
            }
            $stars = ($totalStars/$numIterations);
        }
        else
        {
            $stars = 0;
        }
        return round($stars);
    }
}