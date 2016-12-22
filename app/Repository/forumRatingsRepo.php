<?php

namespace App\Repository;
use Doctrine\ORM\EntityManager;
use App\Entity\forum_rating;

class forumRatingsRepo
{

    /**
     * @var string
     */
    private $class = 'App\Entity\forum_rating';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author rkaur3
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


    public function add($params)
    {
        $forumObj = new forum_rating();
        $forumObj->setRatingStars($params['rating']);
        $userObj = new \App\Repository\usersRepo($this->em);
        $forumObj->setUsers($userObj->getRowObject(['id',$params['user_id']]));
        $fObj = new \App\Repository\forumRepo($this->em);
        $forumObj->setForum($fObj->getRowObject(['id',$params['forum_id']]));
       
        $this->em->persist($forumObj);
        $this->em->flush();

        return $forumObj->getId();

    }

    public function check($params)
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('fr.id')
            ->from('App\Entity\forum_rating','fr')
            ->setParameter(1, $params['forum_id'])
            ->setParameter(2, $params['user_id'] )
            ->where('fr.forum=?1')
            ->andWhere('fr.users=?2');
        return $q_1->getQuery()->getResult();
    }

    public function update($params)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->update('App\Entity\forum_rating', 'fr')
            ->set('fr.rating_stars', '?2')

            ->where('fr.id=?1')

            ->setParameter(1, $params["rating_id"])
            ->setParameter(2, $params['rating']);
        $query = $qb ->getQuery();
        $p = $query->execute();
        return true;
    }

 

   
}