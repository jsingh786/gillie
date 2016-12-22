<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class followw
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="userFollowers")
     * @ORM\JoinColumn(name="follower_user_id", referencedColumnName="id", nullable=false)
     */
    private $followerUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="userFollowed")
     * @ORM\JoinColumn(name="followed_user_id", referencedColumnName="id", nullable=false)
     */
    private $followedUser;

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
    public function getFollowerUser()
    {
        return $this->followerUser;
    }

    /**
     * @param mixed $followerUser
     */
    public function setFollowerUser($followerUser)
    {
        $this->followerUser = $followerUser;
    }

    /**
     * @return mixed
     */
    public function getFollowedUser()
    {
        return $this->followedUser;
    }

    /**
     * @param mixed $followedUser
     */
    public function setFollowedUser($followedUser)
    {
        $this->followedUser = $followedUser;
    }

    
}