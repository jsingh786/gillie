<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 */
class wallpost_comments
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
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="usersWallpostComments")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", nullable=false)
     */
    private $commentsUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\wallpost", inversedBy="wallpostsComments")
     * @ORM\JoinColumn(name="wallpost_id", referencedColumnName="id", nullable=false)
     */
    private $commentsWallpost;

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
    public function getCommentsUser()
    {
        return $this->commentsUser;
    }

    /**
     * @param mixed $commentsUser
     */
    public function setCommentsUser($commentsUser)
    {
        $this->commentsUser = $commentsUser;
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
    public function getCommentsWallpost()
    {
        return $this->commentsWallpost;
    }

    /**
     * @param mixed $commentsWallpost
     */
    public function setCommentsWallpost($commentsWallpost)
    {
        $this->commentsWallpost = $commentsWallpost;
    }


}