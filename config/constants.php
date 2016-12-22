<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/13/2016
 * Time: 5:11 PM
 */


/*
 * Globally used constants.
 */
$HTTP_HOST = @$_SERVER['HTTP_HOST'];
$project_url = 'http://' . $HTTP_HOST;
$project_name = 'gillie/';
$public_path = $project_url . '/' . $project_name . 'public';
return [
    'PROJECT_NAME' => "gillie/",
    'PROJECT_URL' => 'http://' . $HTTP_HOST,
    'PUBLIC_PATH' => $project_url . '/' . $project_name . 'public',
    'SERVER_PUBLIC_PATH' => public_path(),
    'FRONTEND_IMAGE_PATH' => $public_path . '/frontend/images/',
    'BACKEND_IMAGE_PATH' => $public_path . '/backend/images/',
    'REL_IMAGE_PATH' => 'frontend/images',
    'REL_VIDEOS_PATH' => 'frontend/videos',
    'ADMIN_EMAIL' => 'gillieadmin@yopmail.com',
    'APP_NAME' => 'Gillie Network',


    'no_records_found' => 'Oops! No records found as per your search criteria. Please try again with different filters.',
    'you_may_use_filters_to_search_locals' => 'You may use available filters to search locals under this tab.',

    //Forums
    'forum_title_is_required' => 'Forum title is required.',
    'forum_title_should_only_contain_alphabets_numbers_and_spaces.' => 'Forum title should only contain Alphabets, 
        numbers and spaces.',
    'forum_title_should_not_exceed_255_characters' => 'Forum title should not exceed 255 characters.',
    'forum_category_is_required' => 'Forum category is required.',
    'forum_title_is_required' => 'Forum title is required.',
    'forum_title_should_only_contain_alphabets_numbers_and_spaces.' => 'Forum title should only contain Alphabets, numbers and spaces.',
    'forum_title_should_not_exceed_255_characters' => 'Forum title should not exceed 255 characters.',
    'forum_category_is_required' => 'Forum category is required.',

    //Profile_photo Messages
    'uploaded_file_is_not_in_image_format' => 'Uploaded file is not in image format',
    'image_is_required' => 'Image is required',

    //Following
    'not_following_anybody' => 'not following anybody.'
]
?>
