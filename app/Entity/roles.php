<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class roles
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $role_name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\users", mappedBy="userRoles")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\admin", mappedBy="adminRoles")
     */
    private $adminCredentials;

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
    public function getRoleName()
    {
        return $this->role_name;
    }

    /**
     * @param mixed $role_name
     */
    public function setRoleName($role_name)
    {
        $this->role_name = $role_name;
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
    public function getAdminCredentials()
    {
        return $this->adminCredentials;
    }

    /**
     * @param mixed $adminCredentials
     */
    public function setAdminCredentials($adminCredentials)
    {
        $this->adminCredentials = $adminCredentials;
    }

    
}