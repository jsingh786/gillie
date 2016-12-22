<?php
namespace App\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 */
class events
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
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $event_image;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $from_date;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $to_date;

    /**
     * @ORM\Column(type="time", nullable=false)
     */
    private $from_time;

    /**
     * @ORM\Column(type="time", nullable=false)
     */
    private $to_time;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $event_link;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    private $is_published;

    /**
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\regions", inversedBy="events")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=false)
     */
    private $eventRegion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\users", inversedBy="events")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $eventUsers;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getEventImage()
    {
        return $this->event_image;
    }

    /**
     * @param mixed $event_image
     */
    public function setEventImage($event_image)
    {
        $this->event_image = $event_image;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->from_date;
    }

    /**
     * @param mixed $from_date
     */
    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->to_date;
    }

    /**
     * @param mixed $to_date
     */
    public function setToDate($to_date)
    {
        $this->to_date = $to_date;
    }

    /**
     * @return mixed
     */
    public function getFromTime()
    {
        return $this->from_time;
    }

    /**
     * @param mixed $from_time
     */
    public function setFromTime($from_time)
    {
        $this->from_time = $from_time;
    }

    /**
     * @return mixed
     */
    public function getToTime()
    {
        return $this->to_time;
    }

    /**
     * @param mixed $to_time
     */
    public function setToTime($to_time)
    {
        $this->to_time = $to_time;
    }

    /**
     * @return mixed
     */
    public function getEventLink()
    {
        return $this->event_link;
    }

    /**
     * @param mixed $event_link
     */
    public function setEventLink($event_link)
    {
        $this->event_link = $event_link;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * @param mixed $is_published
     */
    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;
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
    public function getEventRegion()
    {
        return $this->eventRegion;
    }

    /**
     * @param mixed $eventRegion
     */
    public function setEventRegion($eventRegion)
    {
        $this->eventRegion = $eventRegion;
    }

    /**
     * @return mixed
     */
    public function getEventUsers()
    {
        return $this->eventUsers;
    }

    /**
     * @param mixed $eventUsers
     */
    public function setEventUsers($eventUsers)
    {
        $this->eventUsers = $eventUsers;
    }

    
}