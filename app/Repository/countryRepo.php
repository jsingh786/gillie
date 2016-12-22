<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 12:17 PM
 */

namespace App\Repository;
use App\Entity\country as country;
use Doctrine\ORM\EntityManager;


class countryRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\country';

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
     * Adds country 
     * @param string $countryName
     * @return country|null.
     * @author rawatabhisishek
     */
    public function addCountry($countryName)
    {
        $countryObj = $this->getRowObject(['name', $countryName]);
        if ($countryObj == null) {
            $country = new country();
            $country->setName($countryName);
            $this->em->persist($country);
            $this->em->flush();
            return $country;
        }
        else{
            return $countryObj;
        }
    }
    }