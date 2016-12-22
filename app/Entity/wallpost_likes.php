<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 */
class wallpost_likes
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
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="usersWallpostLikes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $likesuser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\wallpost", inversedBy="wallpostsLikes")
     * @ORM\JoinColumn(name="wallpost_id", referencedColumnName="id", nullable=false)
     */
    private $likesWallpost;

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
    public function getLikesWallpost()
    {
        return $this->likesWallpost;
    }

    /**
     * @param mixed $likesWallpost
     */
    public function setLikesWallpost($likesWallpost)
    {
        $this->likesWallpost = $likesWallpost;
    }

    /**
     * @return mixed
     */
    public function getLikesuser()
    {
        return $this->likesuser;
    }

    /**
     * @param mixed $likesuser
     */
    public function setLikesuser($likesuser)
    {
        $this->likesuser = $likesuser;
    }


}