<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/10/2016
 * Time: 6:26 PM
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;


use App\Repository\notificationsRepo as notificationsRepo;
use App\Repository\usersRepo as usersRepo;
use App\Service\Notification\Notification as notificationService;

class NotificationsController extends Controller
{


    public function __construct(usersRepo $usersRepo,
                                notificationsRepo $notificationsRepo)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->notificationsRepo = $notificationsRepo;
        $this->usersRepo = $usersRepo;
    }

    public function index(){

        return view('frontend.notifications.notifications-detail')->with('enable_profile_menu',true)->with('profileHolderObj',Auth::user());

    }
    /**
     * Get user's notification
     * @author hkaur5
     * @param Request $request
     * @return json_encoded notification_data array
     */
    public function getNotifications(Request $request)
    {

        $response = array();
        $params = ['for_user_id'=>$request['for_user_id']];
        $notifications_data =  $this->notificationsRepo->get($params, $request['offset'], $request['limit']);
        $notification_service = new notificationService;
        if($notifications_data['notifications'])
        {
            foreach($notifications_data['notifications'] as $key=>$notification)
            {
               // $notifications['notifications'][$key]['text'] =  $notification->getAboutUsersNotification()->getFirstname().' '.$notification->getNotificationtype()->getDescription();
                $notifications['notifications'][$key]['text'] =  $notification_service->getNotificationDescription(1,
                                                                    $notification->getNotificationtype()->getDescription(),
                                                                    $notification->getAboutUsersNotification()->getFirstname().' '.$notification->getAboutUsersNotification()->getLastname());
                $notifications['notifications'][$key]['id'] =  $notification->getId();
                $notifications['notifications'][$key]['for_user_id'] = $notification->getForUsersNotification();
                $notifications['notifications'][$key]['about_user_id'] = $notification->getAboutUsersNotification()->getId();
            }
            $response['notifications_data'] = $notifications;
            $response['notifications_data']['is_more_records'] = $notifications_data['is_more_records'];
        }
        else
        {
            $response['notifications_data'] = 0;
        }


        
        return json_encode($response);


    }

    /**
     * Call notification repo function to delete notification.
     * @param $id
     * @author hkaur5
     * @return boolean true or false
     */
    public function deleteNotification(Request $request)
    {
        $notification_obj = $this->notificationsRepo->getRowObject(['id',$request['id']]);

        if($notification_obj)
        {
            $is_deleted = $this->notificationsRepo->delete($request['id']);
            if($is_deleted){
                return json_encode(1);
            }
            else{
                return false;
            }
        }
        else{
            return json_encode(2);

        }

    }
    
    

}