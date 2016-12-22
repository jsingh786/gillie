<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class followers
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="followerUser")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $usersFollower;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="followedUser")
     * @ORM\JoinColumn(name="users_id2", referencedColumnName="id")
     */
    private $usersFollowed;

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
    public function getUsersFollower()
    {
        return $this->usersFollower;
    }

    /**
     * @param mixed $usersFollower
     */
    public function setUsersFollower($usersFollower)
    {
        $this->usersFollower = $usersFollower;
    }

    /**
     * @return mixed
     */
    public function getUsersFollowed()
    {
        return $this->usersFollowed;
    }

    /**
     * @param mixed $usersFollowed
     */
    public function setUsersFollowed($usersFollowed)
    {
        $this->usersFollowed = $usersFollowed;
    }


}