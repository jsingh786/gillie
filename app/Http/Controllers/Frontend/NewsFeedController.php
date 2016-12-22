<?php
/**
 * Created by PhpStorm.
 * User: rawatabhishek
 * Date: 11/3/2016
 * Time: 11:37 AM
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Facades\Config;

use App\Service\Wallposts\Wallposts as wallpostsService;
use App\Repository\usersRepo as userRepo;
use App\Repository\wallpostRepo as wallpostRepo;
use App\Service\Users\Profile as userProfileService;
use App\Repository\followwRepo as followwRepo;
use App\Repository\wallpostPhotosRepo as wallpostPhotosRepo;
use App\Repository\wallpostLikesRepo as wallpostLikesRepo;
use App\Repository\wallpostCommentsRepo as wallpostCommentsRepo;
use App\Repository\wallpostVideosRepo as wallpostVideosRepo;

class NewsFeedController extends Controller
{
    public function __construct(userRepo $usersRepo,
                                wallpostRepo $wallpostRepo,
                                followwRepo $followwRepo,
                                userProfileService $userProfileService,
                                wallpostPhotosRepo $wallpostPhotosRepo,
                                wallpostLikesRepo  $wallpostLikesRepo,
                                wallpostCommentsRepo  $wallpostCommentsRepo,
                                wallpostVideosRepo $wallpostVideosRepo,
                                wallpostsService $wallpostsService
    )
    {
        parent::__construct();
        $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->wallpostRepo = $wallpostRepo;
        $this->userProfileService = $userProfileService;
        $this->followwRepo = $followwRepo;
        $this->wallpostPhotosRepo = $wallpostPhotosRepo;
        $this->wallpostLikesRepo = $wallpostLikesRepo;
        $this->wallpostCommentsRepo = $wallpostCommentsRepo;
        $this->wallpostVideosRepo = $wallpostVideosRepo;
        $this->wallpostsService = $wallpostsService;

    }
    
    /* Render view for news feed
     * @author rawatabhishek
     */
    public function index($user_id)
    {
        $params = array('id', $user_id);
        $user_obj =  $this->userRepo->getRowObject($params);
        return view('frontend/news-feed')->with('enable_profile_menu', true)->with('profileHolderObj', $user_obj);
    }

    /**
     * Call repo function to add wallpost of type 1.
     * and return wallpost related data.
     * @param Request $request
     * @author hkaur5
     * @return json encoded wallpost array.
     */
    public function addTextWallpost(Request $request){

        //Params for add wallpost function.
        $params = ['user_id'=>Auth::Id(),
        'wallpost_text'=>$request['wallpost_text'],
        'wallpost_type'=>wallpostRepo::WALLPOST_TYPE_TEXT];

        //Add wallpost.
        $wallpost_obj = $this->wallpostRepo->add($params);

        if($wallpost_obj){
           $wallpost_data['wallpost_text'] = $wallpost_obj->getText();
           $wallpost_data['wallpost_posted_by_user_name'] = $wallpost_obj->getWallpostUser()->getFirstname().' '.$wallpost_obj->getWallpostUser()->getLastname();
           $wallpost_data['wallpost_created_at_ISO8601'] = $wallpost_obj->getCreatedAt()->format('Y-m-d\TH:i:s');
           $wallpost_data['wallpost_created_at'] = $wallpost_obj->getCreatedAt()->format('M j, Y');
           $wallpost_data['wallpost_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($wallpost_obj->getWallpostUser()->getId());
           $wallpost_data['logged_in_user_photo'] = $this->userProfileService->getUserProfilePhoto(Auth::Id());
            return json_encode($wallpost_data);
        }
        else{
            return json_encode(false);
        }
    }

    /**
     * Get the wall posts for the user's wall.
     *
     * @author rawatabhishek
     * @param Request $request [//Todo: Define possible parameters in POST request.]
     * @return JSON
     * @version 1.0
     */
    public function getUserWallpost(Request $request) //Todo: Change method name to getWallPostsForUser()
    {
        //get logged In user's following users.
        //Todo: Change the name of $followerUserObj to $followedUsersCollec
        $followerUserObj = $this->followwRepo->followedUsers($request->userId, []);//Todo: Make second parameter optional.
        $followedUserArray = array();
        //Todo: Use foreach loop inside if condition.
        for ($i = 0; $i < count($followerUserObj); $i++) {
            //Todo: I do not think that we need to mention $i for array indexing.
            $followedUserArray[$i] = $followerUserObj[$i]->getFollowedUser()->getId();
        }

        //Get current user and his following users wall posts
        //Todo: Again nop need to mention count($followedUserArray) for array indexing.
        $followedUserArray[count($followedUserArray)] = $request->userId;

        //Todo: Change $wallPostsObj to $wallPostsCollec
        $wallPostsObj = $this->wallpostRepo->getWallposts($followedUserArray,
            ['limit' => $request->limit, 'offset' => $request->offset]);

        //Todo: What is requirement of creating $postId array? Why we are not looping on $wallPostsObj.
        $postId = array(); //Todo: Why name is $postId if it is array.
        for ($i = 0; $i < count($wallPostsObj); $i++) {
            $postId[$i] = $wallPostsObj[$i]->getWallpostUser()->getId();
        }

        //Get user wall posts details
        $wallposts = array();
        foreach ($postId as $key => $value) {
            $wallposts[$key]['wallpost_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($value);
            $wallposts[$key]['wallpost_text'] = $wallPostsObj[$key]->getText();
            $wallposts[$key]['wallpost_id'] = $wallPostsObj[$key]->getId();

            //Get user wall post likes details
            $likeObj = $this->wallpostLikesRepo->isWallpostLikedByUser(['userId'=>Auth::Id(),'wallpostId'=>$wallPostsObj[$key]->getId()]);
            if($likeObj){
                //Todo: Change rowId to row_id.
                $wallposts[$key]['rowId'] = $likeObj[0]->getId();
            }

            //Get user wall post likes count
            $likeCountObj = $this->wallpostLikesRepo->getLikesCount($wallPostsObj[$key]->getId());
            if($likeCountObj) {
                //Todo: change likescount to likes_count.
                $wallposts[$key]['likescount'] = count($likeCountObj);
            }
            //Get two wallpost comments
            $commentObj = $this->wallpostCommentsRepo->getWallPostComment(['limit' => 2, 'offset' => 0], $wallPostsObj[$key]->getId());
            if($commentObj) {
                foreach ($commentObj as $keyy => $value) {
                    //Todo: Change commentinfo to comment_info
                    $wallposts[$key]['commentinfo'][$keyy]['comment_text'] = $value->getText();
                    $wallposts[$key]['commentinfo'][$keyy]['comment_posted_by_user_name'] = $value->getCommentsUser()->getFirstname().' '.$value->getCommentsUser()->getLastname();
                    $wallposts[$key]['commentinfo'][$keyy]['comment_created_at_ISO8601'] = $value->getCreatedAt()->format('Y-m-d\TH:i:s');
                    $wallposts[$key]['commentinfo'][$keyy]['comment_created_at'] = $value->getCreatedAt()->format('M j, Y');
                    $wallposts[$key]['commentinfo'][$keyy]['comment_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($value->getCommentsUser()->getId());
                    $wallposts[$key]['commentinfo'][$keyy]['logged_in_user_photo'] = $this->userProfileService->getUserProfilePhoto(Auth::Id());
                }
            }
            
        }

        //Todo: No need to add if else, just return array.
        if ($wallposts) {
            return json_encode($wallposts);
        }
        else
        {
            return json_encode(0);
        }
    }

    /**
     * Intialise JqueryFileUploadPhotosUpdate for adding photos to
     * wallpost folder of current user.
     *
     * @author hkaur5
     * @return array of file uploaded.
     */
    public function initialiseJqueryFileUploadPhotosUpdate(){

        $user_id = Auth::Id();
        $upload_dir_url = public_path() . '/frontend/images/wallposts/temp_storage/user_' . $user_id . '/';
        $upload_url = url('/') . '/frontend/images/wallposts/temp_storage/user_' . $user_id . '/';

        $upload_handler = new \App\Library\Jqueryfileuploader_uploadhandler(
            array(
                'script_url' => '/add-photos-to-wallpost',
                'upload_dir' => $upload_dir_url,
                'upload_url' => $upload_url,
                'user_dirs' => false,
                'mkdir_mode' => 0755,
                'param_name' => 'files',
                // Set the following option to 'POST', if your server does not support
                // DELETE requests. This is a parameter sent to the client:
                'delete_type' => 'DELETE',
                'access_control_allow_origin' => '*',
                'access_control_allow_credentials' => false,
                'access_control_allow_methods' => array(
                    'OPTIONS',
                    'HEAD',
                    'GET',
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE'
                ),
                'access_control_allow_headers' => array(
                    'Content-Type',
                    'Content-Range',
                    'Content-Disposition'
                ),
                // Enable to provide file downloads via GET requests to the PHP script:
                //     1. Set to 1 to download files via readfile method through PHP
                //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
                //     3. Set to 3 to send a X-Accel-Redirect header for nginx
                // If set to 2 or 3, adjust the upload_url option to the base path of
                // the redirect parameter, e.g. '/files/'.
                'download_via_php' => false,
                // Read files in chunks to avoid memory limits when download_via_php
                // is enabled, set to 0 to disable chunked reading of files:
                'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
                // Defines which files can be displayed inline when downloaded:
                'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
                // Defines which files (based on their names) are accepted for upload:
                // 						'accept_file_types' => '/.+$/i',
                'accept_file_types' => '/\.(gif|jpe?g|png)$/i',
                // The php.ini settings upload_max_filesize and post_max_size
                // take precedence over the following max_file_size setting:
                'max_file_size' => null,
                'min_file_size' => 1,
                // The maximum number of files for the upload directory:
                'max_number_of_files' => null,
                // Defines which files are handled as image files:
                'image_file_types' => '/\.(gif|jpe?g|png)$/i',
                // Use exif_imagetype on all files to correct file extensions:
                'correct_image_extensions' => false,
                // Image resolution restrictions:
                'max_width' => null,
                'max_height' => null,
                'min_width' => 1,
                'min_height' => 1,
                // Set the following option to false to enable resumable uploads:
                'discard_aborted_uploads' => true,
                // Set to 0 to use the GD library to scale and orient images,
                // set to 1 to use imagick (if installed, falls back to GD),
                // set to 2 to use the ImageMagick convert binary directly:
                'image_library' => 1,
                // Uncomment the following to define an array of resource limits
                // for imagick:
                /*
                 'imagick_resource_limits' => array(
                     imagick::RESOURCETYPE_MAP => 32,
                     imagick::RESOURCETYPE_MEMORY => 32
                 ),
        */
                // Command or path for to the ImageMagick convert binary:
                'convert_bin' => 'convert',
                // Uncomment the following to add parameters in front of each
                // ImageMagick convert call (the limit constraints seem only
                // to have an effect if put in front):
                /*
                 'convert_params' => '-limit memory 32MiB -limit map 32MiB',
        */
                // Command or path for to the ImageMagick identify binary:
                'identify_bin' => 'identify',
                'image_versions' => array(
                    // The empty image version key defines options for the original image:
                    'thumbnail' => array(
                        /* 'crop' => true,*/
                        'max_width' => 800,
                        'max_height' => 800
                    ),
                ),
                'print_response' => true
            )
        );

        die;
    }

    /**
     * Intialise JqueryFileUploadPhotosUpdate for adding video to
     * wallpost folder of current user.
     *
     * @author hkaur5
     * @return array of file uploaded.
     */
    public function initialiseJqueryFileUploadVideoUpdate(){

        $user_id = Auth::Id();
        $upload_dir_url = public_path() . '/frontend/videos/wallposts/temp_storage/user_' . $user_id . '/';
        $upload_url = url('/') . '/frontend/videos/wallposts/temp_storage/user_' . $user_id . '/';

        $upload_handler = new \App\Library\Jqueryfileuploader_uploadhandler(
            array(
                'script_url' => '/add-videos-to-wallpost',
                'upload_dir' => $upload_dir_url,
                'upload_url' => $upload_url,
                'user_dirs' => false,
                'mkdir_mode' => 0755,
                'param_name' => 'files',
                //Set the following option to 'POST', if your server does not support
                //DELETE requests. This is a parameter sent to the client:
                'delete_type' => 'DELETE',
                'access_control_allow_origin' => '*',
                'access_control_allow_credentials' => false,
                'access_control_allow_methods' => array(
                    'OPTIONS',
                    'HEAD',
                    'GET',
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE'
                ),
                'access_control_allow_headers' => array(
                    'Content-Type',
                    'Content-Range',
                    'Content-Disposition'
                ),
                // Enable to provide file downloads via GET requests to the PHP script:
                //     1. Set to 1 to download files via readfile method through PHP
                //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
                //     3. Set to 3 to send a X-Accel-Redirect header for nginx
                // If set to 2 or 3, adjust the upload_url option to the base path of
                // the redirect parameter, e.g. '/files/'.
                'download_via_php' => false,
                // Read files in chunks to avoid memory limits when download_via_php
                // is enabled, set to 0 to disable chunked reading of files:
                'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
                // Defines which files can be displayed inline when downloaded:
                'inline_file_types' => '/\.(mp4)$/i',
                // Defines which files (based on their names) are accepted for upload:
                // 						'accept_file_types' => '/.+$/i',
                'accept_file_types' => '/\.(mp4)$/i',
                // The php.ini settings upload_max_filesize and post_max_size
                // take precedence over the following max_file_size setting:
                'max_file_size' => null,
                'min_file_size' => 1,
                // The maximum number of files for the upload directory:
                'max_number_of_files' => null,
                // Defines which files are handled as image files:
                'image_file_types' => '/\.(mp4)$/i',
                // Use exif_imagetype on all files to correct file extensions:
                'correct_image_extensions' => false,
                // Image resolution restrictions:
                'max_width' => null,
                'max_height' => null,
                'min_width' => 1,
                'min_height' => 1,
                // Set the following option to false to enable resumable uploads:
                'discard_aborted_uploads' => true,
                // Set to 0 to use the GD library to scale and orient images,
                // set to 1 to use imagick (if installed, falls back to GD),
                // set to 2 to use the ImageMagick convert binary directly:
                'image_library' => 1,
                // Uncomment the following to define an array of resource limits
                // for imagick:
                /*
                 'imagick_resource_limits' => array(
                     imagick::RESOURCETYPE_MAP => 32,
                     imagick::RESOURCETYPE_MEMORY => 32
                 ),
        */
                // Command or path for to the ImageMagick convert binary:
                'convert_bin' => 'convert',
                // Uncomment the following to add parameters in front of each
                // ImageMagick convert call (the limit constraints seem only
                // to have an effect if put in front):
                /*
                 'convert_params' => '-limit memory 32MiB -limit map 32MiB',
        */
                // Command or path for to the ImageMagick identify binary:
                'identify_bin' => 'identify',
                'image_versions' => array(
                    // The empty image version key defines options for the original image:
                    'thumbnail' => array(
                        /* 'crop' => true,*/
                        'max_width' => 800,
                        'max_height' => 800
                    ),
                ),
                'print_response' => true
            )
        );

        die;
    }

    /**
     * Call model function to add wallpost of type 2.
     * @author hkaur5
     * @param Request $request
     * @return jsson encoded wallpost array.
     */
    public function addPhotosWallpost(Request $request){

        //Todo: I thing we can use Auth::Id() directly without initialising $logged_in_user_id.
        //Todo: Variables shall be in camel case for this project.
        $logged_in_user_id     = Auth::Id();
        //Checking count. It should not be more than 10.
        if ( \Helper_common::countDirectoryFiles( Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/images/wallposts/temp_storage/user_'.$logged_in_user_id.'/thumbnail/' ) <= 50 )
        {
            $params = ['user_id'=>$logged_in_user_id,
                'wallpost_text'=>$request['wallpost_text'],
                'wallpost_type'=>wallpostRepo::WALLPOST_TYPE_PHOTOS];

            $wallpost_obj = $this->wallpostRepo->add($params);

            $rel_image_path        = Config::get('constants.REL_IMAGE_PATH');
            $server_public_path    = Config::get('constants.SERVER_PUBLIC_PATH');
            $public_path           = Config::get('constants.PUBLIC_PATH');

            //getting paths to user default wallpost directory to store photos.
            $userDirectory         = $rel_image_path.'\\wallposts\\user_'.$logged_in_user_id;
            $userWallpostDirectory = $rel_image_path.'\\wallposts\\user_'.$logged_in_user_id.'\\wallpost_'.$wallpost_obj->getId();


            // Creating directories.
            if ( !file_exists( $userDirectory ) )
            {
                mkdir( $userDirectory, 0777, true );
                mkdir( $userWallpostDirectory, 0777, true );
            }
            else
            {
                //This case will occur when user directory exists but wallpost dir does not.
                if ( !file_exists( $userWallpostDirectory ) )
                {
                    mkdir( $userWallpostDirectory, 0777, true );
                }
            }

            //Get photos array
            $dir_array = \Helper_common::dirToArray($server_public_path.'/frontend/images/wallposts/temp_storage/user_'.$logged_in_user_id);

            //Copy all photos to user's wallpost directory from wallpost temp directory.
            foreach ( $dir_array['thumbnail'] as $thumbnail )
            {
                @rename( $server_public_path.'/frontend/images/wallposts/temp_storage/user_'.$logged_in_user_id.'/thumbnail/'.$thumbnail,
                    $userWallpostDirectory.'\\'.$thumbnail );
            }

            //Delete photos from temp directory.
            \Helper_common::deleteDir($server_public_path.'/frontend/images/wallposts/temp_storage/user_'.$logged_in_user_id);
            if( $wallpost_obj )
            {
                $photos_name_id_arr = $this->wallpostPhotosRepo->add($wallpost_obj->getId(), $dir_array['thumbnail'] );
            }
            else
            {
                $return_r['success'] = 0;
                //Todo: Use generic message from constants.
                $return_r['msg'] = "Oops! An error occured while posting your album. Please try again.";
                echo json_encode( $return_r );
                die;
            }
        }
        else
        {
            $return_r['success'] = 0;
            //Todo: Use generic message from constants.
            $return_r['msg'] = "Oops! You can post maximum 50 photos only.";
            echo json_encode( $return_r );
            die;
        }
    }

    /**
     * Call model function to add wallpost of type 3.
     * @author hkaur5
     * @param Request $request
     * @return json encoded wallpost array.
     */
    public function addVideoWallpost(Request $request){

        $logged_in_user_id     = Auth::Id();

       /* dd(\Helper_common::countDirectoryFiles( Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/videos/wallposts/temp_storage/user_'.$logged_in_user_id ));
        die;*/
        //Checking count. It should not be more than 1.
        if ( \Helper_common::countDirectoryFiles( Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/videos/wallposts/temp_storage/user_'.$logged_in_user_id ) <= 1 )
        {
            $params = ['user_id'=>$logged_in_user_id,
                'wallpost_text'=>$request['wallpost_text'],
                'wallpost_type'=>wallpostRepo::WALLPOST_TYPE_VIDEO];

            $wallpost_obj = $this->wallpostRepo->add($params);


            $rel_videos_path    = Config::get('constants.REL_VIDEOS_PATH');
            $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
            $public_path        = Config::get('constants.PUBLIC_PATH');

            //getting paths to user default album directory to store images.
            $userDirectory       = $rel_videos_path . '\\wallposts\\user_' . $logged_in_user_id;
            $userWallpostDirectory       = $rel_videos_path . '\\wallposts\\user_' . $logged_in_user_id.'\\wallpost_'.$wallpost_obj->getId();
            $userWallpostThumbDirectory  = $rel_videos_path . '\\wallposts\\user_' . $logged_in_user_id.'\\wallpost_'.$wallpost_obj->getId().'\\thumbnails';


            // Creating directories.
            if (!file_exists($userDirectory)) {

                mkdir($userDirectory, 0777, true);
                mkdir($userWallpostDirectory, 0777, true);
                mkdir($userWallpostThumbDirectory, 0777, true);
            }
            else{
                if(!file_exists($userWallpostDirectory))
                {
                    mkdir($userWallpostDirectory, 0777, true);
                    mkdir($userWallpostThumbDirectory, 0777, true);
                }
            }
            //Copy files to directories.
            $video_names_array = \Helper_common::dirToArray($server_public_path . '/frontend/videos/wallposts/temp_storage/user_' . $logged_in_user_id);


           // dd($video_names_array);
            if ($video_names_array[0]) {
                $video_params_arr = array();
                $video_params_arr['name'] = $video_names_array[0];
                $video_params_arr['wallpost_id'] = $wallpost_obj->getId();
                $wallpost_video_obj = $this->wallpostVideosRepo->add($video_params_arr);
                if($wallpost_video_obj)
                {
                    $video_thumb_name = \Helper_common::removeFileExtension($wallpost_video_obj->getName());

                    //Moving videos to user's folder from temp folder.
                    @rename($server_public_path . '/frontend/videos/wallposts/temp_storage/user_' . $logged_in_user_id . '/' . $wallpost_video_obj->getName(),
                        $userWallpostDirectory . '\\' . $wallpost_video_obj->getName());

                    //Creating video's thumbnails.
                    // pass ffmpegpath+source path of video as first parameter and destination path of thumbnail as third parameter.
                    $cmd = 'C:\ffmpeg\bin\ffmpeg -i ' . $server_public_path . '\frontend\videos\wallposts\user_' . $logged_in_user_id . '\\wallpost_' .$wallpost_obj->getId().'\\'. $wallpost_video_obj->getName() .' -ss 00:00:15.435 -vframes 1 ' .$server_public_path . '\frontend\videos\wallposts\user_' . $logged_in_user_id . '\\wallpost_'.$wallpost_obj->getId().'\\thumbnails\\' . $video_thumb_name . '.jpg';
                    exec($cmd . " 2>&1", $out, $ret);

                    //Delete temp folder of user.
                    \Helper_common::deleteDir($server_public_path . '/frontend/videos/wallposts/temp_storage/user_' . $logged_in_user_id);

                    $return_r['success'] = 1;
                    //Todo: Use generic message from constants.
                    $return_r['msg'] = "Videos Posted.";
                    echo json_encode($return_r);
                    die;
                }
                else
                {
                    $return_r['success'] = 0;
                    //Todo: Use generic message from constants.
                    $return_r['msg'] = "Oops! An error occurred while saving video.";

                    echo json_encode($return_r);
                    die;
                }

            } else {
                $return_r['success'] = 0;
                //Todo: Use generic message from constants.
                $return_r['msg'] = "Oops! An error occurred while posting video. Please try again.";
                echo json_encode($return_r);
                die;
            }

        }
        else
        {

            $return_r['success'] = 0;
            $return_r['msg'] = "Oops! You can post maximum 1 video at a time.";
            echo json_encode( $return_r );
            die;
        }
    }



    /**
     * Removing the temporary folders
     * of wallposts created while photos are
     * added in wallpost by current user.
     * @author hkaur5
     * @version 1.0
     */
    public function removeTempFoldersOfWallposts()
    {
        $user_id = Auth::Id();
        \Helper_common::deleteDir(Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/images/wallposts/temp_storage/user_'.$user_id.'/');
        \Helper_common::deleteDir(Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/videos/wallposts/temp_storage/user_'.$user_id.'/');

    }

    /**
     * Like a wallpost.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function likeWallPost(Request $request)
    {
        if($request->wallpostId)
        {
            $LikeId = $this->wallpostLikesRepo->likeUser(['userId'=>Auth::Id(),'wallpostId'=>$request->wallpostId]);
            $likeCountObj = $this->wallpostLikesRepo->getLikesCount($request->wallpostId);
            if($likeCountObj && $LikeId) {
                return json_encode(['rowId'=>$LikeId, 'likescount'=>count($likeCountObj)]);
            }
            else {
                return json_encode(false);
            }
        }
        else {
            return json_encode(false);
        }
    }

    /**
     * Unlike a wall post.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function unlikeWallPost(Request $request)
    {
        if($request->rowId) {
            $status = $this->wallpostLikesRepo->unlikeUser($request->rowId);
            $likeCountObj = $this->wallpostLikesRepo->getLikesCount($request->wallpostId);
            if($likeCountObj && $status == true) {
                return json_encode(['rowId'=>$status, 'likescount'=>count($likeCountObj)]);
            }
            else {
                return json_encode(false);
            }
        }
        else {
            return json_encode(false);
        }
    }

//    /**
//     * Check whether the user is liking the wall post or not.
//     * @author rawatabhishek
//     * @param Request $request
//     * @return JSON
//     * @version 1.0
//     */
//    public function isWallpostLikedByUser(Request $request)
//    {
//        if($request->wallpostId) {
//            $status = $this->wallpostLikesRepo->checkUserLike(['UserId'=>Auth::Id(),'wallpostId'=>$request->wallpostId]);
//            if($status)
//            {
//                return json_encode(true);
//            }
//            else {
//                return json_encode(false);
//            }
//        }
//
//    }


    /**
     * Post commment to a wall post.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function addCommentToWallPost(Request $request)
    {
        if($request->wallpostId && $request->text) {
            $wallpostCommentsObj = $this->wallpostCommentsRepo->addComment(['userId'=>Auth::Id(),'wallpostId'=>$request->wallpostId], $request->text);
            $data = array();
            if($wallpostCommentsObj)
            {
                $data['comment_text'] = $wallpostCommentsObj->getText();
                $data['comment_posted_by_user_name'] = $wallpostCommentsObj->getCommentsUser()->getFirstname().' '.$wallpostCommentsObj->getCommentsUser()->getLastname();
                $data['comment_created_at_ISO8601'] = $wallpostCommentsObj->getCreatedAt()->format('Y-m-d\TH:i:s');
                $data['comment_created_at'] = $wallpostCommentsObj->getCreatedAt()->format('M j, Y');
                $data['comment_posted_by_user_photo'] = $this->userProfileService->getUserProfilePhoto($wallpostCommentsObj->getCommentsUser()->getId());
                $data['logged_in_user_photo'] = $this->userProfileService->getUserProfilePhoto(Auth::Id());
                return json_encode($data);
            }
            else {
                return json_encode(false);
            }
        }
        else {
            return json_encode(false);
        }
    }

    /**
     * Remove comment from wall post.
     * @author rawatabhishek
     * @param Request $request
     * @return JSON
     * @version 1.0
     */
    public function removeCommentFromWallPost(Request $request)
    {
        if($request->rowId) {
            $status = $this->wallpostCommentsRepo->removeComment($request->rowId);
            if($status)
            {
                return json_encode(true);
            }
            else {
                return json_encode(false);
            }
        }

    }

}