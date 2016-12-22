<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/9/2016
 * Time: 3:23 PM
 */
namespace App\Repository;
use App\Entity\Weapons as weapons;
use Doctrine\ORM\EntityManager;


class weaponsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\Weapons';

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