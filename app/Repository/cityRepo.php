<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 12:17 PM
 */

namespace App\Repository;
use App\Entity\city as city;
use Doctrine\ORM\EntityManager;


class cityRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\city';

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
     * Add state to city table,
     * @param string $state
     * @param string $countryId(id of country in country table to which this city belongs to)
     * @param integer $stateId(id of state in state table to which this city belongs to)
     * @return object
     * @author rawatabhisishek
     */
    public function add($city, $countryId, $stateId)
    {
        if ($city && $countryId && $stateId) {
            $result = $this->getRowObject(['name', $city]);
            if ($result == null) {
                $countryRepo = new \App\Repository\countryRepo($this->em);
                $countryObj = $countryRepo->getRowObject(['id', $countryId]);
                $stateRepo = new \App\Repository\stateRepo($this->em);
                $stateObj = $stateRepo->getRowObject(['id', $stateId]);
                //  dd($stateObj);
                $cityObj = new city();
                $cityObj->setName($city);
                $cityObj->setState($stateObj);
                $cityObj->setCountry($countryObj);
                $this->em->persist($cityObj);
                $this->em->flush();
                return $cityObj;

            } else {
                return $result;
            }
        }
    }


    /**
     * Get cities of state.
     * @param integer $state_id
     * @author hkaur5
     * @return array of objects of cities.
     */
    public function get($state_id)
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('city')
            ->from('App\Entity\city','city')
            ->orderBy('city.name', 'asc')
            ->where('city.state = ?1')
            ->setParameter(1, $state_id );
        $result = $q_1->getQuery()->getResult();
        return $result;
    }
}