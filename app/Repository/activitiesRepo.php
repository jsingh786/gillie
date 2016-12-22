<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 12:17 PM
 */

namespace App\Repository;
use App\Entity\activities as activities;
use Doctrine\ORM\EntityManager;


class activitiesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\activities';

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
     * Return array of objects.
     * @return null|object
     * @author hkaur5
     */
    public function getAll()
    {
        return $this->em->getRepository($this->class)->findAll();

    }
}