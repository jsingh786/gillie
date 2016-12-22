<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 12:17 PM
 */

namespace App\Repository;
use App\Entity\state as state;
use Doctrine\ORM\EntityManager;


class stateRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\state';

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
     * Add state to state table, //Todo: Why comma at the end of the line?
     * @param string $state     // Todo: Rename it to $name.
     * @param string $countryId //Todo: This is not string but integer.
     * @return object
     * @author rawatabhisishek
     */
    public function addState($state, $countryId) //Todo: As we are already working under State class, rename function to add().
    {
//        echo  $state; //Todo: Please remove commented code.
        if ($state && $countryId) {
            $result = $this->getRowObject(['name', $state]); //Todo: Rename $result to $stateObj.
            if ($result == null) { //Todo: You may write it like this ==> if ($result)
                /*       echo "msns";
                       die;*/
                $countryRepo = new \App\Repository\countryRepo($this->em);
                $countryObj = $countryRepo->getRowObject(['id', $countryId]);
                $stateObj = new state();
                $stateObj->setName($state);
                $stateObj->setCountry($countryObj); //Todo: Pass $countryRepo->getRowObject(['id', $countryId]) directly into the method.
                $this->em->persist($stateObj);
                $this->em->flush();
                return $stateObj;
            } else {
                return $result;
            }
            //Todo: Return once outside of if else.
        }
    }


    /**
     * <INCOMPLETE>
     *
     * Get state.
     * (parametrize this function to add where conditions)
     *
     * @author hkaur5
     * @return array of objects of states.
     */
    public function get()
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('state')
            ->from('App\Entity\state','state');
        $q_1->orderBy('state.name', 'asc');
        $result = $q_1->getQuery()->getResult();
        return $result;
    }


}

