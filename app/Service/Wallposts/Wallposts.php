<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/21/2016
 * Time: 3:00 PM
 */

namespace App\Service\Wallposts;

use App\Repository\usersRepo as userRepo;
use App\Repository\wallpostRepo as wallpostRepo;
use App\Service\Users\Profile as userProfileService;
use App\Repository\followwRepo as followwRepo;
use App\Repository\wallpostPhotosRepo as wallpostPhotosRepo;
use App\Repository\wallpostLikesRepo as wallpostLikesRepo;
use App\Repository\wallpostCommentsRepo as wallpostCommentsRepo;
use App\Repository\wallpostVideosRepo as wallpostVideosRepo;

use Illuminate\Support\Facades\Config;
use Auth;

Class Wallposts
{
    public function __construct(userRepo $usersRepo,
                                wallpostRepo $wallpostRepo,
                                followwRepo $followwRepo,
                                userProfileService $userProfileService,
                                wallpostPhotosRepo $wallpostPhotosRepo,
                                wallpostLikesRepo  $wallpostLikesRepo,
                                wallpostCommentsRepo  $wallpostCommentsRepo,
                                wallpostVideosRepo $wallpostVideosRepo
    )
    {
        $this->userRepo = $usersRepo;
        $this->wallpostRepo = $wallpostRepo;
        $this->userProfileService = $userProfileService;
        $this->wallpostPhotosRepo = $wallpostPhotosRepo;
        $this->wallpostLikesRepo = $wallpostLikesRepo;
        $this->wallpostCommentsRepo = $wallpostCommentsRepo;
        $this->wallpostVideosRepo = $wallpostVideosRepo;

    }


    public function getWallpostInfo( $wallpost_obj ){

        $wallpost_data['wallpost_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($wallpost_obj->getWallpostUser()->getId());
        $wallpost_data['wallpost_text'] = $wallpost_obj->getText();
        $wallpost_data['wallpost_id'] = $wallpost_obj->getId();

        $likeObj = $this->wallpostLikesRepo->isWallpostLikedByUser(['userId'=>Auth::Id(),'wallpostId'=>$wallpost_obj->getId()]);
        if($likeObj){
            $wallpost_data['rowId'] = $likeObj[0]->getId();
        }

        $comments_obj = $this->wallpostCommentsRepo->getWallPostComment(['limit' => 2, 'offset' => 0], $wallpost_obj->getId());
        if($comments_obj) {
            foreach ($comments_obj as $key => $comment_obj) {
                $wallpost_data['comments'][$key]['comment_text'] = $comment_obj->getText();
                $wallpost_data['comments'][$key]['comment_posted_by_user_name'] = $comment_obj->getCommentsUser()->getFirstname().' '.$comment_obj->getCommentsUser()->getLastname();
                $wallpost_data['comments'][$key]['comment_created_at_ISO8601'] = $comment_obj->getCreatedAt()->format('Y-m-d\TH:i:s');
                $wallpost_data['comments'][$key]['comment_created_at'] = $comment_obj->getCreatedAt()->format('M j, Y');
                $wallpost_data['comments'][$key]['comment_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($value->getCommentsUser()->getId());
                $wallpost_data['comments'][$key]['logged_in_user_photo'] = $this->userProfileService->getUserProfilePhoto(Auth::Id());
            }
        }

        if ($wallpost_obj->getType() == 2){

            $collage_data = self::getWallpostCollageInfo($wallpost_obj);
            $wallpost_data['collage'] = $collage_data;
            dd($wallpost_data['collage']);

        }
    }

    /**
     * Get photos of wallpost.
     * Create an array which contain id and path of first
     * five photos of wallpost.
     *
     * @param object $wallpost_obj
     * @return array collage [0=>['photo_path'=>'','photo_id'=>1],1=>['photo_path'=>'','photo_id'=>2]]
     */
    public function getWallpostCollageInfo($wallpost_obj){

        $public_path           = Config::get('constants.PUBLIC_PATH');
        $params['wallpost_id'] = $wallpost_obj->getId();
        $wallpost_photos       = $this->wallpostPhotosRepo->get($params);
      //  dd($wallpost_photos);
        $i = 0;
        $photo_info = '';
        foreach ( $wallpost_photos as $key=>$wallpost_photo )
        {
            $return_r['collage_photos'][$i]['photo_path'] = $public_path."/frontend/images/wallposts/user_".$wallpost_obj->getWallpostUser()->getId()."/wallpost_".$wallpost_obj->getId()."/".$wallpost_photo->getName();
            $return_r['collage_photos'][$i]['photo_id'] = $wallpost_photo->getId();
            if( $i == 4 )
            {
                break;
            }
            $i++;
        }

        $size = getimagesize( $return_r['collage_photos'][0]['photo_path'] );
        $width = $size[0];
        $height = $size[1];
        $aspect = $height / $width;
        if ($aspect >= 1)
        {
            //vertical
            $return_r['first_img_portrait_or_landscape'] = 1;
        }
        else
        {
            //horizontal
            $return_r['first_img_portrait_or_landscape'] = 2;
        }
        return $return_r;
    }
}