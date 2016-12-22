<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
/**
 * Just implement the ShouldBroadcast interface and Laravel will automatically
 * send it to Pusher once we fire it
 **/
class HelloPusherEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Only (!) Public members will be serialized to JSON and sent to Pusher
     **/
    public $message;
    public $id;
    public $for_user_id;

    /**
     * Create a new event instance.
     * @param string $message (notification description)
     * @param integer $id (notification id)
     * @param integer $for_user_id (receiver's id)
     * @author hkaur5
     * @return void
     */
    public function __construct($message,$id, $for_user_id)
    {
        $this->message = $message;
        $this->id = $id;
        $this->for_user_id = $for_user_id;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['my-channel'];
    }
}
