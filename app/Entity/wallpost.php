<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 */
class wallpost
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var datetime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_likes", mappedBy="likesWallpost", cascade={"remove"})
     */
    private $wallpostsLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_comments", mappedBy="commentsWallpost", cascade={"remove"})
     */
    private $wallpostsComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_photos", mappedBy="photosWallpost")
     */
    private $wallpostsPhotos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_videos", mappedBy="videosWallpost", cascade={"remove"})
     */
    private $wallpostsVideo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="usersWallposts")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", nullable=false)
     */
    private $wallpostUser;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getWallpostsLikes()
    {
        return $this->wallpostsLikes;
    }

    /**
     * @param mixed $wallpostsLikes
     */
    public function setWallpostsLikes($wallpostsLikes)
    {
        $this->wallpostsLikes = $wallpostsLikes;
    }

    /**
     * @return mixed
     */
    public function getWallpostsComments()
    {
        return $this->wallpostsComments;
    }

    /**
     * @param mixed $wallpostsComments
     */
    public function setWallpostsComments($wallpostsComments)
    {
        $this->wallpostsComments = $wallpostsComments;
    }

    /**
     * @return mixed
     */
    public function getWallpostsPhotos()
    {
        return $this->wallpostsPhotos;
    }

    /**
     * @param mixed $wallpostsPhotos
     */
    public function setWallpostsPhotos($wallpostsPhotos)
    {
        $this->wallpostsPhotos = $wallpostsPhotos;
    }

    /**
     * @return mixed
     */
    public function getWallpostsVideo()
    {
        return $this->wallpostsVideo;
    }

    /**
     * @param mixed $wallpostsVideo
     */
    public function setWallpostsVideo($wallpostsVideo)
    {
        $this->wallpostsVideo = $wallpostsVideo;
    }

    /**
     * @return mixed
     */
    public function getWallpostUser()
    {
        return $this->wallpostUser;
    }

    /**
     * @param mixed $wallpostUser
     */
    public function setWallpostUser($wallpostUser)
    {
        $this->wallpostUser = $wallpostUser;
    }

}