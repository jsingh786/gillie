<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class user_hunting_land
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $favourite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\hunting_land", inversedBy="userHuntingLand")
     * @ORM\JoinColumn(name="hunting_land_id", referencedColumnName="id")
     */
    private $huntingLand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="userHuntingLand")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     */
    private $users;

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
    public function getFavourite()
    {
        return $this->favourite;
    }

    /**
     * @param mixed $users
     */
    public function setFavourite($favourite)
    {
        $this->favourite = $favourite;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getHuntingLand()
    {
        return $this->huntingLand;
    }

    /**
     * @param mixed $activities
     */
    public function setHuntingLand($huntingLand)
    {
        $this->huntingLand = $huntingLand;
    }

}