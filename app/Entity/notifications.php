<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 */
class notifications
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
    private $is_read;

    /**
     * @var datetime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="notificationsForUser")
     * @ORM\JoinColumn(name="for_user_id", referencedColumnName="id", nullable=false)
     */
    private $forUsersNotification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="notificationsAboutUser")
     * @ORM\JoinColumn(name="about_user_id", referencedColumnName="id", nullable=false)
     */
    private $aboutUsersNotification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\notification_types", inversedBy="notifications")
     * @ORM\JoinColumn(name="notification_type_id", referencedColumnName="id", nullable=false)
     */
    private $notificationType;

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
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * @param mixed $is_read
     */
    public function setIsRead($is_read)
    {
        $this->is_read = $is_read;
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
    public function getForUsersNotification()
    {
        return $this->forUsersNotification;
    }

    /**
     * @param mixed $forUsersNotification
     */
    public function setForUsersNotification($forUsersNotification)
    {
        $this->forUsersNotification = $forUsersNotification;
    }

    /**
     * @return mixed
     */
    public function getAboutUsersNotification()
    {
        return $this->aboutUsersNotification;
    }

    /**
     * @param mixed $aboutUsersNotification
     */
    public function setAboutUsersNotification($aboutUsersNotification)
    {
        $this->aboutUsersNotification = $aboutUsersNotification;
    }

    /**
     * @return mixed
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }

    /**
     * @param mixed $notificationType
     */
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;
    }


}