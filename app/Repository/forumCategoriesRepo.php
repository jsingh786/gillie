<?php
/**
 * Created by PhpStorm.
 * User: rkaur3
 * Date: 8/31/2016
 * Time: 12:56 PM
 */
namespace App\Repository;
use Doctrine\ORM\EntityManager;
use App\Entity\forum_categories;

class forumCategoriesRepo{

    /**
     * @var string
     */
    private $class = 'App\Entity\forum_categories';

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

    /**
     * Get all categories.
     *
     * @author rkaur3
     * @version 1.0
     * Dated 31-aug-2016
     */
    public function getAll()
    {
        return $this->em->getRepository($this->class)->findAll();
    }
}