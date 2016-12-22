<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/22/2016
 * Time: 3:27 PM
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;

//Repositories.
use App\Repository\usersRepo as userRepo;
use App\Repository\userProfileRepo as userProfileRepo;
use App\Repository\albumRepo as albumRepo;
use App\Repository\photosRepo as photosRepo;
use App\Repository\videosRepo as videosRepo;

use Illuminate\Support\Facades\Config;
use App\Exceptions;
use Auth;
use Mockery\CountValidator\Exception;
use Validator;
use Cookie;
use Illuminate\Support\Facades\Input;


class AlbumsController extends Controller
{

    private $usersRepo;

    private $userNotesRepo;

    public function __construct(userRepo $usersRepo,
                                userProfileRepo $userProfileRepo,
                                albumRepo $albumRepo,
                                photosRepo $photosRepo,
                                videosRepo $videosRepo
    )
    {

        parent::__construct();
        $this->middleware('auth');
        $this->userRepo = $usersRepo;
        $this->userProfileRepo = $userProfileRepo;
        $this->albumRepo = $albumRepo;
        $this->photosRepo = $photosRepo;
        $this->videosRepo = $videosRepo;

    }

    

    /**
     * Render gallery view(blade template).
     *
     * @param integer $user_id (id of user whose albums page we are viewing)
     * @return $this
     */
    public function index($user_id)
    {
        $data = array('enable_profile_menus'=>true);
        $user_obj =  $this->userRepo->getRowObject(['id',$user_id]);
        
        return view('frontend/gallery')->with('data', $data)->with('profileHolderObj', $user_obj);
    }
    public function initialiseJqueryFileUpload()
    {

        $user_id = Auth::Id();

        $upload_handler = new \App\Library\Jqueryfileuploader_uploadhandler(
            array(
                'script_url' => 'initialise-jquery-file-upload',
                'upload_dir' => public_path().'\frontend\images\albums\temp_storage_for_gallery\user_'.$user_id.'\/',
                'upload_url' => url('/').'/frontend/images/albums/temp_storage_for_gallery/user_'.$user_id.'/',
                'user_dirs' => false,
                'mkdir_mode' => 0777,
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
                    /* 'original_photos' => array(
                               // Automatically rotate images based on EXIF meta data:
                             //  'auto_orient' => true
                           ),*/
                    'thumbnail' => array(
                        'crop' => false,
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
     * For posting photos in existing album.
     * @param Request $request
     * @author hkaur5
     * @return Array files.
     */
    public function initialiseJqueryFileUploadPhotosToAlbum(Request $request)
    {

        $user_id = Auth::Id();
        $album_id =  $request['album_id'];
        $album_obj = $this->albumRepo->getRowObject(['id',$album_id]);
        {
            if($album_obj){
                $upload_dir_url = public_path().'/frontend/images/albums/temp_storage_for_gallery/user_'.$user_id.'/album_'.$album_obj->getId().'/';
                $upload_url = url('/').'/frontend/images/albums/temp_storage_for_gallery/user_'.$user_id.'/album_'.$album_obj->getId().'/';

            }
            else
            {
                $upload_dir_url = public_path() . '/frontend/images/albums/temp_storage_for_gallery/user_' . $user_id . '/';
                $upload_url = url('/') . '/frontend/images/albums/temp_storage_for_gallery/user_' . $user_id . '/';
            }
        }
        $upload_handler = new \App\Library\Jqueryfileuploader_uploadhandler(
            array(
                'script_url' => '/initialise-jquery-file-upload-add-photos-to-album',
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
     * Removing the temporary folders
     * of albums created while album uploading
     * in gallery by current user.
     *
     * @author hkaur5
     * @version 1.0
     */
    public function removeTempFolderOfAlbums()
    {
        $user_id = Auth::Id();
        \Helper_common::deleteDir(Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/images/albums/temp_storage_for_gallery/user_'.$user_id.'/');

    }


    /**
     * Save album and photos data and move album from temp folder to
     * albums folder.
     * @param Request $request
     * @author hkaur5
     * @return string
     */
    public function postAlbum(Request $request)
    {
        $logged_in_user_id     = Auth::Id();

        //Checking count. It should not be more than 50.
        if ( \Helper_common::countDirectoryFiles( Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/thumbnail/' ) <= 50 )
        {

            $rel_image_path        = Config::get('constants.REL_IMAGE_PATH');
            $server_public_path    = Config::get('constants.SERVER_PUBLIC_PATH');
            $public_path           = Config::get('constants.PUBLIC_PATH');
            $album_data            = array();
            $album_data['name']    = $request['album_name'];
            $album_data['user_id'] = $logged_in_user_id;


            //Adding album data to database.
            $album_obj   = $this->albumRepo->add( $album_data );
            $album_name  = $album_obj->getName();

            //getting paths to user default album directory to store images.
            $userDirectory      = $rel_image_path.'\\albums\\user_'.$logged_in_user_id;
            $userAlbumDirectory = $rel_image_path.'\\albums\\user_'.$logged_in_user_id.'\\album_'.$album_name;
            $popupDirecory   = $rel_image_path.'\\albums\\user_'.$logged_in_user_id.'\\album_'.$album_name.'\\popup_thumbnails';

            // Creating directories.
            if ( !file_exists( $userDirectory ) )
            {
                mkdir( $userDirectory, 0777, true );
                mkdir( $userAlbumDirectory, 0777, true );
                mkdir( $popupDirecory, 0777, true );
            }
            else
            {
                //This case will occur when user directory exists but album dir does not.
                if ( !file_exists( $userAlbumDirectory ) )
                {
                    mkdir( $userAlbumDirectory, 0777, true );
                    mkdir( $popupDirecory, 0777, true );
                }
            }

            //Copy files to directories.
            $dir_array = \Helper_common::dirToArray($server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id);

            // dd($dir_array['thumbnail']);

            foreach ( $dir_array['thumbnail'] as $thumbnail )
            {
                @rename( $server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/thumbnail/'.$thumbnail,
                    $popupDirecory.'\\'.$thumbnail );
            }


            \Helper_common::deleteDir($server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id);
            if( $album_data )
            {
                $photos_name_id_arr = $this->photosRepo->add($album_obj->getId(), $dir_array['thumbnail'] );
                $return_r['success'] = 1;
                $return_r['msg'] = "Album posted.";
                $album_photos_obj_arr = $this->photosRepo->getAll($album_obj->getId());// Get all photos of album.
                //Get last photo to get album cover.

                if($album_photos_obj_arr)
                {

                    $last_photo_obj = end($album_photos_obj_arr);

                }
                else//If no photos found in album then return failure msg.
                {
                    $return_r['success'] = 0;
                    $return_r['msg'] = "Some error occurred. Please try again.";
                    echo json_encode( $return_r );
                    die;
                }
                $return_r['last_photo_path'] = $public_path.'/frontend/images/albums/user_'.Auth::Id().'/album_'.$album_obj->getName().'/popup_thumbnails/'.$last_photo_obj->getImage();
                $return_r['photo_count'] = count($album_photos_obj_arr);
                $return_r['display_name'] = $request['album_name'];
                $return_r['id'] = $album_obj->getId();
                return json_encode($return_r);
            }
            else
            {
                $return_r['success'] = 0;
                $return_r['msg'] = "Oops! An error occured while posting your album. Please try again.";
                echo json_encode( $return_r );
                die;
            }
        }
        else
        {
            $return_r['success'] = 0;
            $return_r['msg'] = "Oops! You can post maximum 50 photos only.";
            echo json_encode( $return_r );
            die;
        }
    }




    /**
     * Call Get function of Album model
     * and returns array of album data.
     * @author hkaur5
     * @param Request $request
     * @return string
     */
    public function getUsersAlbums(Request $request)
    {
        $public_path = Config::get('constants.PUBLIC_PATH');
        $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
        $albums_data = $this->albumRepo->get($request['userId'], $request['offset'], $request['limit']);
        if($albums_data['albums'])
        {
            foreach($albums_data['albums'] as $key=>$album_data)
            {
                $albums[$key]['id'] = $album_data->getId();

                $albums[$key]['display_name'] = $album_data->getDisplay_name();
                $photos = $this->photosRepo->getAll($album_data->getId());
                if($photos)
                {
                    $albums[$key]['photo_count'] = count($photos);
                    $last_photo = end($photos);
                    if($last_photo)
                    {
                        if(file_exists($server_public_path.'/frontend/images/albums/user_'.$request['userId'].'/album_'.$album_data->getName().'/popup_thumbnails/'.$last_photo->getImage())) {
                            $albums[$key]['last_photo_path'] =  $public_path.'/frontend/images/albums/user_'.$request['userId'].'/album_'.$album_data->getName().'/popup_thumbnails/'.$last_photo->getImage();
                        }
                        else{
                        $albums[$key]['last_photo_path'] =  $public_path.'/frontend/images/no_image.png';

                    }
                    }
                }

            }
            if(!empty($albums_data['is_more_records']) )
            {
                $data['is_more_records'] = $albums_data['is_more_records'];
            }
            else
            {
                $data['is_more_records'] = 0;
            }

            $data['albums'] = $albums;

        }
        else
        {
            $data['albums']= 0;
        }
        return json_encode($data);
        die;
    }


    /**
     *  Get Photos from photos model class and
     *  return json encoded response containing photo detail.
     * @author hkaur5
     *  @return json_encoded array
     */

    public function getAlbumsPhotos(Request $request)
    {
        $public_path = Config::get('constants.PUBLIC_PATH');
        $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');

        $album_obj = $this->albumRepo->getRowObject(['id',$request['album_id']]);

        $photos_data = $this->photosRepo->get($request['album_id'], $request['offset'], $request['limit']);
        $data['album_display_name'] = $album_obj->getDisplay_name();
        if($photos_data['photos'])
        {
            foreach($photos_data['photos'] as $key=>$photo_data)
            {
                $photos[$key]['id'] = $photo_data->getId();
                $photos[$key]['name'] = $photo_data->getImage();
                $photos[$key]['album_posted_by'] = $photo_data->getAlbums()->getUsers()->getId();
                $album_owner_id = $photo_data->getAlbums()->getUsers()->getId();
               // $photos[$key]['path'] = $public_path.'/frontend/images/albums/user_'.Auth::Id().'/album_'.$photo_data->getAlbums()->getName().'/gallery_thumbnails/'.$photo_data->getImage();
                if(file_exists($server_public_path.'/frontend/images/albums/user_'.$album_owner_id.'/album_'.$photo_data->getAlbums()->getName().'/popup_thumbnails/'.$photo_data->getImage())){

                    $photos[$key]['path_popup_thumbnail'] = $public_path.'/frontend/images/albums/user_'.$album_owner_id.'/album_'.$photo_data->getAlbums()->getName().'/popup_thumbnails/'.$photo_data->getImage();
                }
                else{
                $photos[$key]['path_popup_thumbnail'] = $public_path.'/frontend/images/no_image.png';

            }
            }
            if(!empty($photos_data['is_more_records']) )
            {
                $data['is_more_records'] = $photos_data['is_more_records'];
            }
            else
            {
                $data['is_more_records'] = 0;
            }

            $data['photos'] = $photos;

        }
        else
        {
            $data['photos']= 0;
        }
        return json_encode($data);
        die;
    }


    /**
     * Return view for album detail page ( display album detail page )
     * @param $id
     * @author hkaur5
     * @return view for album detail page ( display album detail page )
     */
    public function albumDetail($id, $user_id)
    {
       // dd($user_id);
        $album_obj = $this->albumRepo->getRowObject(['id', $id]);

        //If album does not exist then redirect to gallery page of logged in user.
        if(!$album_obj)
        {
            return redirect('gallery/user-id/'.Auth::Id());
        }
        /*if(Auth::Id() != $album_obj->getUsers()->getId())
        {
            return redirect('gallery/user-id/'.Auth::Id());
        }*/
        $user_obj =  $this->userRepo->getRowObject(['id',$user_id]);
        return view('frontend/album-detail')->with('album_id', $id)->with('profileHolderObj', $user_obj);
    }

    /**
     * Call Model function to delete album
     * Delete Album directory.
     * @author hkaur5
     * @param Request $request
     * @return int 0/1
     */
    public function deleteAlbum(Request $request)
    {
        try
        {
            $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
            $album_obj = $this->albumRepo->getRowObject(['id',$request['id']]);

            $this->albumRepo->delete($request['id']);
            \Helper_common::deleteDir($server_public_path.'/frontend/images/albums/user_'.Auth::Id().'/album_'.$album_obj->getName());
            return json_encode(1);
        }
        catch(Exception $e)
        {
            return json_encode(0);
        }

    }

    /**
     * Call model function to delete album. If after deleting photo
     * no more photos exists in album then delete album.
     * It also unlink photos from directories and Delete album directory
     * if no more photos exist
     * @author hkaur5
     * @param Request $request
     * @return array (array('success'=>0/1,'last_photo'=>'true/false'))
     */
    public function deletePhoto(Request $request)
    {
        $return_r = array();
        $return_r['last_photo'] = false;
        try
        {
            $rel_image_path = Config::get('constants.REL_IMAGE_PATH');
            $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');
            $photo_obj = $this->photosRepo->getRowObject(['id',$request['id']]);
            $this->photosRepo->delete($request['id']);

            //Unlink image file.
            unlink($rel_image_path."/albums/user_".Auth::Id()."/album_".$photo_obj->getalbums()->getName()."/popup_thumbnails/".$photo_obj->getImage());

            //Check if no more photos exist in this album. Then delete album as well.
            $albums_photos = $this->photosRepo->get($photo_obj->getAlbums()->getId());
            if(! $albums_photos['photos'])
            {
                $this->albumRepo->delete($photo_obj->getAlbums()->getId());
                \Helper_common::deleteDir($server_public_path.'/frontend/images/albums/user_'.Auth::Id().'/album_'.$photo_obj->getAlbums()->getName());
                redirect('gallery/user-id/'.Auth::Id());
                $return_r['last_photo'] = true;
            }

            $return_r['success'] = true;

            return json_encode($return_r);
        }
        catch(Exception $e)
        {
            $return_r['success'] = false;
            return json_encode($return_r);
        }
    }


    /**
     * Initialise jquery uploader class object
     * @param Request $request
     * @author hkaur5
     * @return string
     */
    public function postPhotosToAlbum(Request $request)
    {
        $logged_in_user_id     = Auth::Id();
        $album_id  = $request['album_id'];

        //Checking count. It should not be more than 50.
        if ( \Helper_common::countDirectoryFiles( Config::get('constants.SERVER_PUBLIC_PATH').'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/album_'.$album_id.'/thumbnail/' ) <= 50 )
        {

            $rel_image_path        = Config::get('constants.REL_IMAGE_PATH');
            $server_public_path    = Config::get('constants.SERVER_PUBLIC_PATH');
            $public_path           = Config::get('constants.PUBLIC_PATH');
            $album_obj             = $this->albumRepo->getRowObject(['id', $album_id] );
            $album_name            = $album_obj->getName();

            //Getting paths to user default album directory to store images.
            $userDirectory      = $rel_image_path.'\\albums\\user_'.$logged_in_user_id;
            $userAlbumDirectory = $rel_image_path.'\\albums\\user_'.$logged_in_user_id.'\\album_'.$album_name;
            $popupDirecory   = $rel_image_path.'\\albums\\user_'.$logged_in_user_id.'\\album_'.$album_name.'\\popup_thumbnails';

            // Creating directories.
            if ( !file_exists( $userDirectory ) )
            {
                mkdir( $userDirectory, 0777, true );
                mkdir( $userAlbumDirectory, 0777, true );
                mkdir( $popupDirecory, 0777, true );

            }
            //
            else
            {
                //This case will occur when user directory exists but album dir does not.
                if ( !file_exists( $userAlbumDirectory ) )
                {
                    mkdir( $userAlbumDirectory, 0777, true );
                    mkdir( $popupDirecory, 0777, true );
                }
            }

            //Copy files to directories.
            $dir_array = \Helper_common::dirToArray($server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/album_'.$album_id);



            foreach ( $dir_array['thumbnail'] as $thumbnail )
            {
                @rename( $server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/album_'.$album_id.'/thumbnail/'.$thumbnail,
                    $popupDirecory.'\\'.$thumbnail );
            }

            \Helper_common::deleteDir($server_public_path.'/frontend/images/albums/temp_storage_for_gallery/user_'.$logged_in_user_id.'/album_'.$album_id);

                $photos_arr = $this->photosRepo->add($album_obj->getId(), $dir_array['thumbnail'] );
                $return_r['photos_count'] = count($photos_arr);
                foreach($photos_arr as $key=>$photo)
                {
               //     $return_r['photos'][$key]['path'] = $public_path.'/frontend/images/albums/user_'.$logged_in_user_id.'/album_'.$album_name.'/gallery_thumbnails/thumbnail_'.$photo['name'];
                    $return_r['photos'][$key]['id'] = $photo['id'];
                    $return_r['photos'][$key]['name'] = $photo['name'];
                    $return_r['photos'][$key]['path_popup_thumbnail'] = $public_path.'/frontend/images/albums/user_'.$logged_in_user_id.'/album_'.$album_name.'/popup_thumbnails/'.$photo['name'];
                }

            $return_r['success'] = 1;
            $return_r['msg'] = 'Photos posted.';
            return json_encode($return_r);
        }
        else
        {
            $return_r['success'] = 0;
            $return_r['msg'] = "Oops! You can post maximum 50 photos only.";
            echo json_encode( $return_r );
            die;
        }
    }


    public function updateAlbumDetail(Request $request)
    {
        $logged_in_user_id = Auth::Id();
        $album_data = array();
        $album_data['display_name'] = $request['album_name'];
        $album_data['id'] = $request['id'];
        $album_obj = $this->albumRepo->update($album_data);

        if($album_obj)
        {
            $return_r = array();
            $return_r['album_display_name'] = $album_obj->getDisplay_name();
            $return_r['msg'] = "Updated";
            $return_r['success'] = 1;

        }
        else
        {
            $return_r['msg'] = 'Album name not update. Please try again.';
            $return_r['success'] = 0;
        }
        return json_encode($return_r);
        die;
    }


    public function getGalleryItems(Request $request)
    {
        $public_path = Config::get('constants.PUBLIC_PATH');
        $server_public_path = Config::get('constants.SERVER_PUBLIC_PATH');

        // Video tile Data
        $params['user_id'] = $request['userId'];
        $videos_obj_arr = $this->videosRepo->getAll($params);

        //Send video data on calling gallery item in document.ready
        //on load more video tile data is not required.
        if($request['get_videos'])
        {

            if ($videos_obj_arr) {

                $videos_arr = array();
                $videos_arr['count'] = count($videos_obj_arr);
                $last_video_obj = end($videos_obj_arr);
                $last_video_name = $last_video_obj->getVideoName();

                $video_thumb_name = \Helper_common::removeFileExtension($last_video_name);

                if(file_exists($server_public_path.'/frontend/videos/gallery/user_' . $request['userId'] . '/thumbnails/' . $video_thumb_name . '.jpg'))
                {
                    $path = $public_path . '/frontend/videos/gallery/user_' . $request['userId'] . '/thumbnails/' . $video_thumb_name . '.jpg';
                }
                else{
                    $path = $public_path.'/frontend/images/no_video_thumb.jpg';
                }
                $videos_arr['last_video_thumb_path'] = $path;

                $return_r['video_tile_data'] = $videos_arr;

            }
            else {
                $return_r['video_tile_data'] = 0;
            }
        }
        else
        {
            $return_r['video_tile_data'] = 0;
        }
        //Video Tile - Ends---

        //Album Data---
        $albums_data = $this->albumRepo->get($request['userId'], $request['offset'], $request['limit']);
        if ($albums_data['albums']) {
            foreach ($albums_data['albums'] as $key => $album_data) {
                $albums[$key]['id'] = $album_data->getId();

                $albums[$key]['display_name'] = $album_data->getDisplay_name();
                $photos = $this->photosRepo->getAll($album_data->getId());
                if ($photos) {
                    $albums[$key]['photo_count'] = count($photos);
                    $last_photo = end($photos);
                    $albums[$key]['last_photo_path_actual'] = $server_public_path.'/frontend/images/albums/user_' . $request['userId'] . '/album_' . $album_data->getName() . '/popup_thumbnails/' . $last_photo->getImage();
                    if ($last_photo) {
                        if(file_exists($server_public_path.'/frontend/images/albums/user_' . $request['userId'] . '/album_' . $album_data->getName() . '/popup_thumbnails/' . $last_photo->getImage()))
                        {

                            $albums[$key]['last_photo_path'] = $public_path . '/frontend/images/albums/user_' . $request['userId'] . '/album_' . $album_data->getName() . '/popup_thumbnails/' . $last_photo->getImage();
                        }
                        else
                        {
                            $albums[$key]['last_photo_path'] = $public_path . '/frontend/images/no_image.png';
                        }
                    }
                }

            }

            $return_r['is_more_records'] = $albums_data['is_more_records'];
            $return_r['albums'] = $albums;
        }
        else{
            $return_r['albums'] = 0;
        }

        return json_encode($return_r);
    }

}