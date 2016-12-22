<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/14/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\profile_rating_algo;
use Doctrine\ORM\EntityManager;

class profileRatingRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\profile_rating_algo';

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
     * Return onject of the row.
     * 
     * @param $id
     * @return null|object
     * @author rkaur3
     * @version 1.0
     */
    public function getRowObject($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    /**
     * Get all rating points
     *
     * @return array of collection
     * @author rkaur3
     * @version 1.0
     * Dated 4 July,2016
     */
    public function getRatings()
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('profile_rating_algo')
            ->from('App\Entity\profile_rating_algo','profile_rating_algo');

        return $q_1->getQuery()->getResult();
    }

    /**
     * Updates Points relevant to stars
     *
     * @param associative array $ratingData
     * @return boolean
     * @author rkaur3
     * @version 1.1
     */
    public function updatePoints($ratingData)
    {
       if(isset($ratingData)) {
           foreach ($ratingData as $rd) {
               $rateObj = $this->getRowObject(['id', $rd['id']]);
               $rateObj->setPoints($rd['points']);
           }
           $this->em->persist($rateObj);
           $this->em->flush();
           return true;
       }

    }

}