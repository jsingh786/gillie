<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 */
class forum
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $no_of_views;


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
    private $activity_at;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $trending;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\forum_comments", mappedBy="forum", cascade={"remove"})
     */
    private $forumComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\forum_rating", mappedBy="forum")
     */
    private $forumRating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\forum_categories", inversedBy="forum")
     * @ORM\JoinColumn(name="forum_categories_id", referencedColumnName="id", nullable=false)
     */
    private $forumCategories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="forum")
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getNoOfViews()
    {
        return $this->no_of_views;
    }

    /**
     * @param mixed $no_of_views
     */
    public function setNoOfViews($no_of_views)
    {
        $this->no_of_views = $no_of_views;
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
    public function getForumRating()
    {
        return $this->forumRating;
    }

    /**
     * @param mixed $forumRating
     */
    public function setForumRating($forumRating)
    {
        $this->forumRating = $forumRating;
    }

    /**
     * @return mixed
     */
    public function getForumCategories()
    {
        return $this->forumCategories;
    }

    /**
     * @param mixed $forumCategories
     */
    public function setForumCategories($forumCategories)
    {
        $this->forumCategories = $forumCategories;
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

    public function getActivityAt()
    {
        return $this->activity_at;
    }

    /**
     * @param mixed $activity_at
     */
    public function setActivityAt($activity_at)
    {
        $this->activity_at = $activity_at;
    }

    /**
     * @return mixed
     */
    public function getTrending()
    {
        return $this->trending;
    }

    /**
     * @param mixed $trending
     */
    public function setTrending($trending)
    {
        $this->trending = $trending;
    }






}