<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/14/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\regions;
use Doctrine\ORM\EntityManager;

class regionsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\regions';

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
     */
    public function getRowObject($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    /**
     * Deletes row.
     * @param Post $post
     */
    public function delete(Post $post)
    {
        $this->em->remove($post);
        $this->em->flush();
    }



}