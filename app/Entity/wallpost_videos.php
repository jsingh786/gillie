<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class wallpost_videos
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_At;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\wallpost", inversedBy="wallpostsVideo")
     * @ORM\JoinColumn(name="wallpost_id", referencedColumnName="id", nullable=false)
     */
    private $videosWallpost;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_At;
    }

    /**
     * @param mixed $created_At
     */
    public function setCreatedAt($created_At)
    {
        $this->created_At = $created_At;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getVideosWallpost()
    {
        return $this->videosWallpost;
    }

    /**
     * @param mixed $videosWallpost
     */
    public function setVideosWallpost($videosWallpost)
    {
        $this->videosWallpost = $videosWallpost;
    }

    
}