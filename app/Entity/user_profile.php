<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class user_profile
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dob;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $occupation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $work;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $college;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $school;

    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $marital_status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\hunting_land", mappedBy="userProfile")
     */
    private $huntingLand;

    /**
     *
     * @ORM\JoinColumn(name="users_id", referencedColumnName="id", unique=true)
     * @ORM\OneToOne(targetEntity="App\Entity\users", inversedBy="userProfile")
     */
    private $users;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_photo_name;

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

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
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @param mixed $occupation
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return mixed
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @param mixed $work
     */
    public function setWork($work)
    {
        $this->work = $work;
    }

    /**
     * @return mixed
     */
    public function getCollege()
    {
        return $this->college;
    }

    /**
     * @param mixed $college
     */
    public function setCollege($college)
    {
        $this->college = $college;
    }

    /**
     * @return mixed
     */
    public function getHuntingLand()
    {
        return $this->huntingLand;
    }
    /**
     * @return mixed
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @param mixed $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
    }
    /**
     * @return mixed
     */
    public function getMarital_status()
    {
        return $this->marital_status;
    }

    /**
     * @param mixed $marital_status
     */
    public function setMarital_status($marital_status)
    {
        $this->marital_status = $marital_status;
    }

    /**
     * @param mixed $huntingLand
     */
    public function setHuntingLand($huntingLand)
    {
        $this->huntingLand = $huntingLand;
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
    public function getProfile_photo_name()
    {
        return $this->profile_photo_name;
    }

    /**
     * @param mixed $users
     */
    public function setProfile_photo_name($profile_photo_name)
    {
        $this->profile_photo_name = $profile_photo_name;
    }
    

}