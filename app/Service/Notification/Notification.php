<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/16/2016
 * Time: 3:52 PM
 */

namespace App\Service\Notification;

class Notification
{

    /**
     * Beta Version.
     *
     * Prepare description for notification.
     *
     * @author hkaur5
     * @param integer $type (notification_type)
     * @param string $description (notification type description)
     * @param string $about_user_name (about user first and last name)
     * @return string notification description
     */
    public function getNotificationDescription($type, $type_description, $about_user_name)
    {
        switch($type){

            case 1:
               $notification_text = $about_user_name.' '.$type_description;
                break;
            default:
                $notification_text = $type_description;
        }
        return $notification_text;
    }
}
