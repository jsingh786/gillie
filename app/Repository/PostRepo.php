<?php
//Todo: This class is in no use, remove this.

/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/13/2016
 * Time: 7:47 PM
 */
namespace App\Repository;
use App\Entity\Post;
use Doctrine\ORM\EntityManager;

class PostRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\Post';
    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function create(Post $post)
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    public function update(Post $post, $data)
    {
        $post->setTitle($data['title']);
        $post->setBody($data['body']);
        $this->em->persist($post);
        $this->em->flush();
    }

    public function PostOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    public function delete(Post $post)
    {
        $this->em->remove($post);
        $this->em->flush();
    }

    /**
     * create Post
     * @return Post
     */
    private function prepareData($data)
    {
        return new Post($data);
    }

    
}