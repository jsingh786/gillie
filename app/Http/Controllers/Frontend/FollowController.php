<?php
/**
 * Created by PhpStorm.
 * User: rawatabhishek
 * Date: 11/4/2016
 * Time: 9:38 AM
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Repository\followwRepo as followwRepo;
use App\Repository\usersRepo as userRepo;
use App\Service\Users\Profile as userProfileService;
use App\Repository\notificationsRepo as notificationsRepo;
use App\Service\Notification\Notification as notificationService;

class FollowController extends Controller
{
    private $followwRepo;

    public function __construct(followwRepo $followwRepo,
                                userRepo $usersRepo,
                                userProfileService $userProfileService,
                                notificationsRepo $notificationsRepo)
    {
        parent::__construct();
        $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->followwRepo = $followwRepo;
        $this->userProfileService = $userProfileService;
        $this->notificationsRepo = $notificationsRepo;
    }

    /**
     * Add a follower to the followws table.
     * @author rawatabhishek
     * @author hkaur5 (add-notification code)
     * @param Request $request(userId)
     * @return JSON
     * @version 1.1
     */
    public function addFollower(Request $request)
    {
//        echo $request->followerId; die;
        $data     = ['followedId'=>$request->followerId,'followerId'=>Auth::Id()];
        $is_following = $this->followwRepo->isUser1followedByUser2(Auth::Id(), $request->followerId);
        if(!$is_following){
            $result   = $this->followwRepo->add($data);
        }
        else{

            return  json_encode(0);
        }


        //===================================Notification Code=====================================//
        //Add notification.
        $notification_data  = ['for_user_id'=>$request->followerId,
            'about_user_id'=>Auth::Id(),
            'is_read'=>0,
            'type_id'=>1];

        $notification = $this->notificationsRepo->add($notification_data);
        //Add notifications - end.

        //Send notification text to Pusher Event.
        $notification_type_text = $notification->getNotificationType()->getDescription();
        $notification_type = $notification->getNotificationType()->getId();
        $notification_about_user_name = $notification->getAboutUsersNotification()->getFirstname().' '.$notification->getAboutUsersNotification()->getLastname();
        $notificationService = new notificationService;
        $notification_description = $notificationService->getNotificationDescription($notification_type,
            $notification_type_text,
            $notification_about_user_name);

        event(new \App\Events\HelloPusherEvent($notification_description,
            $notification->getId(),
            $notification->getForUsersNotification()->getId()));
        //Send notification text to Pusher Event --- END.
        //=====================================Notification Code END=====================================//

        if($result)
        {
            return json_encode($result);
        }
        else{

            return json_encode(0);
        }


    }

    /**
     * Remove a follower to the followws table.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function removeFollower(Request $request)
    {
//        echo $request->rowId; die;
        if($request->rowId) {
            $this->followwRepo->remove($request->rowId);
            return json_encode(0);
        }
        else{
            return json_encode(1);
        }
    }
    
    /**
     * Get followers and their details.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function getFollowed(Request $request)
    {
        $followedUsersIds   = $this->followwRepo->followedUsers($request->userId, ['limit' => 5, 'offset' => 0]);
        $data = array();
        if($followedUsersIds) {

            for ($i = 0; $i < count($followedUsersIds); $i++) {
                $data[$i]['id'] = $followedUsersIds[$i]->getFollowedUser()->getId();
                $data[$i]['name'] = $followedUsersIds[$i]->getFollowedUser()->getFirstname() . ' ' . $followedUsersIds[$i]->getFollowedUser()->getLastname();
                $data[$i]['place'] = $followedUsersIds[$i]->getFollowedUser()->getAddress();
                $data[$i]['path'] = $this->userProfileService->getUserProfilePhoto($followedUsersIds[$i]->getFollowedUser()->getId());
            }
            return json_encode($data);
        }
        return json_encode(0);
    }

    /**
     * Load following users.
     * @author rawatabhishek
     * @param Request $request
     * @param $user_id(id of a row in followws table)
     * @return JSON
     * @version 1.0
     */
    public function index($user_id)
    {
        $user_obj =  $this->userRepo->getRowObject(array('id', $user_id));
        return view('frontend/follow/following')->with('profileHolderObj', $user_obj);
    }

    /**
     * Get followers and their details.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function getFollowingDetails(Request $request)
    {
        $following = $this->followwRepo->followingUsers($request->userId, ['limit' => $request->limit, 'offset' => $request->offset]);
        if (count($following['following']))
        {
            $data = array();
            for ($i = 0; $i < count($following['following']); $i++) {

                $data['following'][$i]['profileImg'] = $following['following'][$i]->getFollowedUser()->getProfileImage();
                $data['following'][$i]['name'] = $following['following'][$i]->getFollowedUser()->getFirstname() . ' ' . $following['following'][$i]->getFollowedUser()->getLastname();
                $data['following'][$i]['place'] = $following['following'][$i]->getFollowedUser()->getAddress();
                $data['following'][$i]['id'] = $following['following'][$i]-> getFollowedUser()->getId();
                $data['following'][$i]['path'] = $this->userProfileService->getUserProfilePhoto($following['following'][$i]->getFollowedUser()->getId());
                $data['following'][$i]['fid'] = $following['following'][$i]->getId();
                $rowid = $this->followwRepo->getlocalUsersRowobject(Auth::Id(), $following['following'][$i]->getFollowedUser()->getId());
                if($rowid) {
                    $data['following'][$i]['followedRowId'] = $rowid[0]->getId();
                }
                else{
                    $data['following'][$i]['followedRowId'] = 0;
                }

                if($this->followwRepo->isUser1followedByUser2(Auth::id(),$following['following'][$i]-> getFollowedUser()->getId())== true){
                    $data['following'][$i]['followingstatus'] = true;
                }
                else{
                    $data['following'][$i]['followingstatus'] = false;
                }

            }

            $data['moreRecords'] = $following['moreRecords'];
            return json_encode($data);
        }
        else
        {
            return json_encode(0);
        }
    }

    /**
     * Search user and fetch users details  .
     * @author rawatabhishek
     * @param Request $request(pattren,limit,offset,userId)
     * @return JSON
     * @version 1.0
     */
    public function searchUsers(Request $request)
    {
        if(!empty($request->pattren)) {
            $pattern_arr = explode(' ', $request->pattren);
            $result = $this->followwRepo->searchFollowingUsers($pattern_arr, $request->userId, ['limit' => $request->limit, 'offset' => $request->offset]);
            if ($result) {
                $data = array();
                for ($i=0; $i < count($result['following']); $i++) {
                    $data['following'][$i]['path'] = $this->userProfileService->getUserProfilePhoto($result['following'][$i]->getFollowedUser()->getId());
                    $data['following'][$i]['name'] = $result['following'][$i]->getFollowedUser()->getFirstname() . ' ' . $result['following'][$i]->getFollowedUser()->getLastname();
                    $data['following'][$i]['place'] = $result['following'][$i]->getFollowedUser()->getAddress();
                    $data['following'][$i]['id'] = $result['following'][$i]->getId();
                }
                $data['moreRecords'] = $result['moreRecords'];
                return json_encode($data);
            }
            return json_encode(0);
        }

    }
    

}