<?php

/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/30/2016
 * Time: 5:31 PM
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;


//Repositories.
use App\Repository\usersRepo as userRepo;
use App\Repository\userProfileRepo as userProfileRepo;
use App\Repository\videosRepo as videosRepo;


use Illuminate\Support\Facades\Config;
use App\Exceptions;
use Auth;
use Mockery\CountValidator\Exception;
use Validator;
use Cookie;
use Illuminate\Support\Facades\Input;

class VideosController extends Controller
{

    private $usersRepo;

    private $userNotesRepo;

    public function __construct(userRepo $usersRepo,
                                userProfileRepo $userProfileRepo,
                                videosRepo $videosRepo
    )
    {

        parent::__construct();
        $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->userProfileRepo = $userProfileRepo;
        $this->videosRepo = $videosRepo;

    }

    /**
     * Save videos data and move videos from user's temp folder to
     * videos folder.
     * @param Request $request
     * @author hkaur5
     * @return string
     */

    public function postVideos(Request $request)
    {
        $logged_in_user_id = Auth::Id();

        //Checking count. It should not be more than 10.
        if (\Helper_common::countDirectoryFiles(Config::get('constants.SERVER_PUBLIC_PATH') . '/frontend/videos/gallery/temp_storage_for_gallery/user_' . $logged_in_user_id . '/') <= 50) {

            $rel_videos_path = Config::get('constants.REL_VIDEOS_PATH');
            $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
            $public_path = Config::get('constants.PUBLIC_PATH');

            //getting paths to user default album directory to store images.
            $userDirectory = $rel_videos_path . '\\gallery\\user_' . $logged_in_user_id;
            $userThumbDirectory = $rel_videos_path . '\\gallery\\user_' . $logged_in_user_id.'\\thumbnails';


            // Creating directories.
            if (!file_exists($userDirectory)) {
                mkdir($userDirectory, 0777, true);
                mkdir($userThumbDirectory, 0777, true);
            }
            else{
                if(!file_exists($userThumbDirectory))
                {
                    mkdir($userThumbDirectory, 0777, true);
                }


            }

            //Copy files to directories.
            $video_names_array = \Helper_common::dirToArray($server_public_path . '/frontend/videos/gallery/temp_storage_for_gallery/user_' . $logged_in_user_id);


            if ($video_names_array) {
                $video_params_arr = array();
                $video_params_arr['names'] = $video_names_array;
                $video_params_arr['user_id'] = Auth::Id();
                $videos_arr = $this->videosRepo->add($video_params_arr);
                if($videos_arr)
                {

                    $count = count($video_names_array);
                    $i = 0;
                    foreach ($video_names_array as $video_name) {


                        $video_thumb_name = \Helper_common::removeFileExtension($video_name);
                        //Moving videos to user's folder.
                        @rename($server_public_path . '/frontend/videos/gallery/temp_storage_for_gallery/user_' . $logged_in_user_id . '/' . $video_name,
                            $userDirectory . '\\' . $video_name);

                        //Creating video's thumbnails.
                        // pass ffmpegpath+source path of video as first parameter and destination path of thumbnail as third parameter.
                        $cmd = 'C:\ffmpeg\bin\ffmpeg -i ' . $server_public_path . '\frontend\videos\gallery\user_' . $logged_in_user_id . '\\' . $video_name .
                            ' -ss 00:00:15.435 -vframes 1 ' .
                            $server_public_path . '\frontend\videos\gallery\user_' . $logged_in_user_id . '\\thumbnails\\' . $video_thumb_name . '.jpg';
                        exec($cmd . " 2>&1", $out, $ret);
                        if($i == $count-1 )
                        {
                            $last_video_thumb_name = $video_thumb_name;
                        }
                        $i++;

                    }

                    //Delete temp folder of user.
                    \Helper_common::deleteDir($server_public_path . '/frontend/videos/gallery/temp_storage_for_gallery/user_' . $logged_in_user_id);
                    $return_r['success'] = 1;
                    $return_r['msg'] = "Videos Posted.";
                    if(file_exists($server_public_path.'/frontend/videos/gallery/user_'.$logged_in_user_id.'/thumbnails/'.$last_video_thumb_name.'.jpg'))
                    {

                        $return_r['last_video_thumb_path'] = $public_path.'/frontend/videos/gallery/user_'.$logged_in_user_id.'/thumbnails/'.$last_video_thumb_name.'.jpg';
                    }
                    else{

                        $return_r['last_video_thumb_path'] = $public_path.'/frontend/images/no_video_thumb.jpg';
                    }
                    $videos = $this->videosRepo->getAll(['user_id'=>Auth::id()]);

                    $return_r['count'] = count($videos);
                    echo json_encode($return_r);
                    die;
                }
                else
                {
                    $return_r['success'] = 0;
                    $return_r['msg'] = "Oops! An error occurred while saving video(s).";

                    echo json_encode($return_r);
                    die;
                }

            } else {
                $return_r['success'] = 0;
                $return_r['msg'] = "Oops! An error occurred while posting your album. Please try again.";
                echo json_encode($return_r);
                die;
            }
        } else {
            $return_r['success'] = 0;
            $return_r['msg'] = "Oops! You can post maximum 10 photos only.";
            echo json_encode($return_r);
            die;
        }
    }


    /**
     * Instantiate Jqueryfileuploader_uploadhandler object
     * @author hkaur5
     *
     */
    public function initialiseJqueryFileUploadForVideos()
    {
        $user_id = Auth::Id();

        $upload_handler = new \App\Library\Jqueryfileuploader_uploadhandler(
            array(
                'script_url' => 'initialise-jquery-file-upload',
                'upload_dir' => public_path().'/frontend/videos/gallery/temp_storage_for_gallery/user_'.$user_id.'/',
                'upload_url' => url('/').'/frontend/videos/gallery/temp_storage_for_gallery/user_'.$user_id.'/',
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
                    /* 'original_photos' => array(
                               // Automatically rotate images based on EXIF meta data:
                             //  'auto_orient' => true
                           ),*/
                    'thumbnail' => array(
                        'crop' => true,
                        'max_width' => 800,
                        'max_height' => 800
                    ),


                ),
                'print_response' => true
            )
        );

    }

    /**
     * Removing the temporary folders
     * of videos created while videos uploading
     * in gallery by current user.
     *
     * @author hkaur5
     * @version 1.0
     */
    public function removeTempFolderOfVideos()
    {
        $user_id = Auth::Id();
        \Helper_common::deleteDir(Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/videos/gallery/temp_storage_for_gallery/user_'.$user_id.'/');
    }

    /**
     * Get user's last video thumb and total video count.
     * @author hkaur5
     * @return json_encoded array(['last_video_thumb_path'=>'','count'=>1]
     */
    public function getUsersVideoLastThumbNCount()
    {
        $params['user_id'] = Auth::Id();

        $videos_obj_arr = $this->videosRepo->getAll($params);
        if($videos_obj_arr)
        {

            $videos_arr = array();
            $videos_arr['count'] = count($videos_obj_arr);
            $last_video_obj = end($videos_obj_arr);
            $last_video_name = $last_video_obj->getVideoName();

            $video_thumb_name = \Helper_common::removeFileExtension($last_video_name);


            $path = Config::get('constants.PUBLIC_PATH').'/frontend/videos/gallery/user_'.Auth::Id().'/thumbnails/'.$video_thumb_name.'.jpg';
            $videos_arr['last_video_thumb_path'] = $path;
            return json_encode($videos_arr);
        }
        else{
            return json_encode(0);
        }
    }

    /**
     * Fetch Videos data of current user
     * and return json_encoded array.
     * @author hkaur5
     * @param Request $request
     * @return json_enoded array of videos and other information
     */
    public function getUsersVideosListing(Request $request)
    {
         $videos_obj_arr = $this->videosRepo->get($request['userId'], $request['offset'], $request['limit']);
        if($videos_obj_arr['videos'])
        {
            $videos = array();
            foreach($videos_obj_arr['videos'] as $key=>$video_obj )
            {
                $video_thumb_name = \Helper_common::removeFileExtension($video_obj->getVideoName());
                if(file_exists(Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/videos/gallery/user_'.$request['userId'].'/thumbnails/'.$video_thumb_name.'.jpg')){

                    $video_thumb_path = Config::get('constants.PUBLIC_PATH').'/frontend/videos/gallery/user_'.$request['userId'].'/thumbnails/'.$video_thumb_name.'.jpg';
                }
                else{
                    $video_thumb_path = Config::get('constants.PUBLIC_PATH').'/frontend/images/no_video_thumb.jpg';

                }
                $video_path = Config::get('constants.PUBLIC_PATH').'/frontend/videos/gallery/user_'.$request['userId'].'/'.$video_obj->getVideoName();
                $videos[$key]['video_thumb_path'] = $video_thumb_path;
                $videos[$key]['video_path'] = $video_path;
                $videos[$key]['name'] = $video_obj->getVideoName();
                $videos[$key]['id'] = $video_obj->getId();

            }
            $return_r['videos'] = $videos;
            $return_r['is_more_records'] = $videos_obj_arr['is_more_records'];
            $return_r['success'] = 1;
            return json_encode($return_r);
        }
        else
        {
            $return_r['videos'] = '';
            $return_r['success'] = 0;
            return json_encode($return_r);
        }
        die;
    }

    /**
     * Return video-listing blade
     * @author hkaur5
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videos($user_id)
    {
        $user_obj =  $this->userRepo->getRowObject(['id',$user_id]);
        return view('frontend/videos-listing')->with('profileHolderObj', $user_obj);
    }

    /**
     * Delete video for given video id.
     * @param Request $request
     * @return json_encoded integer ( 1 = Video deleted from DB and directories,
     * 2 = Deleted from DB but Files are not deleted, 3 = Video could not be deleted from DB)
     */
    public function deleteVideo(Request $request)
    {
        $rel_videos_path = Config::get('constants.REL_VIDEOS_PATH');
        $video_obj = $this->videosRepo->getRowObject(['id',$request['id']]);
        $video_name = $video_obj->getVideoName();
        $video_thumb_name = \Helper_common::removeFileExtension($video_name);
        $delete_video = $this->videosRepo->delete($request['id']);
        if($delete_video)
        {
            try{

                $unlinked_video = @unlink($rel_videos_path."/gallery/user_".Auth::Id()."/".$video_name);
                $unlinked_video_thumb = @unlink($rel_videos_path."/gallery/user_".Auth::Id()."/thumbnails/".$video_thumb_name.'.jpg');
            }
            catch(Exception $e)
            {
                return json_encode(4);
            }
            if($unlinked_video && $unlinked_video_thumb)
            {
                return json_encode(1);//Video deleted from but files not deleted
            }
            else
            {
                return json_encode(2); //Everything went well
            }
        }
        else{
            return json_encode(3); // Video not deleted from db.
        }
    }

    /**
     * Render play video popup blade
     * @author hkaur5
     */
    public function renderVideoPopup($video_id)
    {
        $video_obj = $this->videosRepo->getRowObject(['id',$video_id]);
        
        $video_path = Config::get('constants.PUBLIC_PATH').'/frontend/videos/gallery/user_'.$video_obj->getUsers()->getId().'/'.$video_obj->getVideoName();
        return view('frontend/play-video')->with('video_path',$video_path);
    }
}

