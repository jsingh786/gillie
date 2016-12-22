<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 2:29 PM
 */

namespace App\Repository;
use App\Entity\properties as properties;
use Doctrine\ORM\EntityManager;


class propertiesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\properties';

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