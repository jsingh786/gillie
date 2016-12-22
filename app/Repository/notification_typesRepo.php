<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/10/2016
 * Time: 4:55 PM
 */

namespace App\Repository;
use App\Entity\notification_types as notification_types;
use Doctrine\ORM\EntityManager;


class notification_typesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\notification_types';

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
}