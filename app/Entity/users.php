<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Contracts\Auth\CanResetPassword;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @Gedmo\SoftDeleteable(fieldName="deleted_at")
 */
class users implements  \Illuminate\Contracts\Auth\Authenticatable, CanResetPassword
{
    use \LaravelDoctrine\ORM\Auth\Authenticatable;
    use \Illuminate\Auth\Passwords\CanResetPassword;
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    //private $password;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    private $is_active;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    //private $remember_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    //private $confirmation_token;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $longitude;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\events", mappedBy="eventUsers")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\forum", mappedBy="users", cascade={"remove"})
     */
    private $forum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\forum_comments", mappedBy="users")
     */
    private $forumComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\videos", mappedBy="users")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\album", mappedBy="users")
     */
    private $albums;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\user_profile", mappedBy="users")
     */
    private $userProfile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user_activities", mappedBy="users")
     */
    private $userActivities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user_weapons", mappedBy="users")
     */
    private $userWeapons;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user_species", mappedBy="users")
     */
    private $userSpecies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user_properties", mappedBy="users")
     */
    private $userProperties;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\roles", inversedBy="users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user_notes", mappedBy="users")
     */
    private $userNotes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\followw", mappedBy="followerUser", cascade={"remove"})
     */
    private $userFollowers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\followw", mappedBy="followedUser", cascade={"remove"})
     */
    private $userFollowed;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\notifications", mappedBy="forUsersNotification", cascade={"remove"})
     */
    private $notificationsForUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\notifications", mappedBy="aboutUsersNotification", cascade={"remove"})
     */
    private $notificationsAboutUser;

    /**
     *
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="App\Entity\city", inversedBy="users")
     */
    private $city;

    /**
     *
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="App\Entity\country", inversedBy="users")
     */
    private $country;

    /**
     *
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="App\Entity\state", inversedBy="users")
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost", mappedBy="wallpostUser", cascade={"remove"})
     */
    private $usersWallposts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_likes", mappedBy="likesuser", cascade={"remove"})
     */
    private $usersWallpostLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\wallpost_comments", mappedBy="commentsUser", cascade={"remove"})
     */
    private $usersWallpostComments;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return mixed
     */
    public function getProfileImage()
    {
        return $this->profile_image;
    }

    /**
     * @param mixed $profile_image
     */
    public function setProfileImage($profile_image)
    {
        $this->profile_image = $profile_image;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return mixed
     */
    /*public function getRememberToken()
    {
        return $this->remember_token;
    }*/

    /**
     * @param mixed $remember_token
     */
   /* public function setRememberToken($remember_token)
    {
        $this->remember_token = $remember_token;
    }*/

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmation_token;
    }

    /**
     * @param mixed $confirmation_token
     */
    public function setConfirmationToken($confirmation_token)
    {
        $this->confirmation_token = $confirmation_token;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }


    /**
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param datetime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param datetime $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return mixed
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param mixed $forum
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    /**
     * @return mixed
     */
    public function getForumComments()
    {
        return $this->forumComments;
    }

    /**
     * @param mixed $forumComments
     */
    public function setForumComments($forumComments)
    {
        $this->forumComments = $forumComments;
    }

    /**
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param mixed $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * @return mixed
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    }

    /**
     * @return mixed
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * @param mixed $userProfile
     */
    public function setUserProfile($userProfile)
    {
        $this->userProfile = $userProfile;
    }

    /**
     * @return mixed
     */
    public function getUserActivities()
    {
        return $this->userActivities;
    }

    /**
     * @param mixed $userActivities
     */
    public function setUserActivities($userActivities)
    {
        $this->userActivities = $userActivities;
    }

    /**
     * @return mixed
     */
    public function getUserWeapons()
    {
        return $this->userWeapons;
    }

    /**
     * @param mixed $userWeapons
     */
    public function setUserWeapons($userWeapons)
    {
        $this->userWeapons = $userWeapons;
    }

    /**
     * @return mixed
     */
    public function getUserSpecies()
    {
        return $this->userSpecies;
    }

    /**
     * @param mixed $userSpecies
     */
    public function setUserSpecies($userSpecies)
    {
        $this->userSpecies = $userSpecies;
    }

    /**
     * @return mixed
     */
    public function getUserProperties()
    {
        return $this->userProperties;
    }

    /**
     * @param mixed $userProperties
     */
    public function setUserProperties($userProperties)
    {
        $this->userProperties = $userProperties;
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param mixed $userRoles
     */
    public function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;
    }

    /**
     * @return mixed
     */
    public function getUserNotes()
    {
        return $this->userNotes;
    }

    /**
     * @param mixed $userNotes
     */
    public function setUserNotes($userNotes)
    {
        $this->userNotes = $userNotes;
    }

    /**
     * @return mixed
     */
    public function getUserFollowers()
    {
        return $this->userFollowers;
    }

    /**
     * @param mixed $userFollowers
     */
    public function setUserFollowers($userFollowers)
    {
        $this->userFollowers = $userFollowers;
    }

    /**
     * @return mixed
     */
    public function getUserFollowed()
    {
        return $this->userFollowed;
    }

    /**
     * @param mixed $userFollowed
     */
    public function setUserFollowed($userFollowed)
    {
        $this->userFollowed = $userFollowed;
    }



}
