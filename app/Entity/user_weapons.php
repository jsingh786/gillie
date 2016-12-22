<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class user_weapons
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
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="userWeapons")
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Weapons", inversedBy="userWeapons")
     * @ORM\JoinColumn(name="weapons_id", referencedColumnName="id", nullable=false)
     */
    private $weapons;

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getFavourite()
    {
        return $this->favourite;
    }

    /**
     * @param mixed $favourite
     */
    public function setFavourite($favourite)
    {
        $this->favourite = $favourite;
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
    public function getWeapons()
    {
        return $this->weapons;
    }

    /**
     * @param mixed $weapons
     */
    public function setWeapons($weapons)
    {
        $this->weapons = $weapons;
    }

    
}