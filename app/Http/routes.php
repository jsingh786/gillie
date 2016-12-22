<?php

Route::any('auth/login', 'Auth\AuthController@postLogin');

Route::get('/', 'Frontend\HomeController@index');
Route::get('/searchuser', 'Frontend\HomeController@searchuser');

/**---------Routes for Backend Start---**/

Route::group(['prefix' => 'admin'], function () {

    //-------------------DevController--------------------------------------//
    Route::get('dev', 'Backend\DevController@getIndex');
    Route::get('dev/generate-proxies', 'Backend\DevController@getGenerateProxies');
    Route::get('dev/generate-db', 'Backend\DevController@getGenerateDB');
    Route::get('dev/clear-apc-cache', 'Backend\DevController@clearApcCache');
    Route::get('dev/testing', 'Backend\DevController@testing');
    Route::get('dev/testing-elastic', 'Backend\DevController@testingElastic');
    Route::get('dev/testing-user-elastic', 'Backend\DevController@testingUserElastic');
    Route::get('dev/create-elastic-index', 'Backend\DevController@createElasticSearchIndex');

    //-------------------AdminAuthController--------------------------------------//
    Route::get('/login', 'AdminAuth\AdminAuthController@showLoginForm');
    Route::post('/login', 'AdminAuth\AdminAuthController@postLogin');
    Route::get('/logout', 'AdminAuth\AdminAuthController@logout');
    Route::get('/user', 'AdminAuth\AdminAuthController@getUser');

    //-------------------AdminController--------------------------------------//
    Route::get('/myprofile', 'Backend\AdminController@getProfile');
    Route::get('/userManagement', 'Backend\AdminController@getUserManagement');
    Route::post('/editProfile', 'Backend\AdminController@postEditProfile');
    Route::get('/', 'Backend\AdminController@getIndex');
    Route::get('dashboard', 'Backend\AdminController@getDashboard');
    Route::post('/changePassword', 'Backend\AdminController@postChangePassword');
    Route::get('/adminDetail', 'Backend\AdminController@getAdminDetail');
    Route::post('/get-all-users', 'Backend\AdminController@getAllUsers');

    //-------------------NewsController--------------------------------------//
    Route::get('/news/add-news', 'Backend\NewsController@getAddNews');
    Route::post('news/add-news', 'Backend\NewsController@postAddNews');
    Route::post('news/delete-news', 'Backend\NewsController@postDeleteNews');
    Route::get('news', 'Backend\NewsController@getIndex');
    Route::get('newsData/', 'Backend\NewsController@getNews');
    Route::get('news/edit-news/{id}', 'Backend\NewsController@getEditNews');
    Route::post('news/get-news-detail', 'Backend\NewsController@getNewsDetail');
    Route::get('news/view-news/{id}', 'Backend\NewsController@getNewsDetailView');

    //-------------------UserController--------------------------------------//

    Route::get('users', 'Backend\UserController@index');
    Route::get('users/add-user', 'Backend\UserController@addUser');
    Route::get('users/add-user/{id?}', 'Backend\UserController@addUser');
    Route::post('users/save-new-user', 'Backend\UserController@saveNewUser');
    Route::get('users/get-user/{id}', 'Backend\UserController@getUserDetails');
    Route::post('users/delete-user', 'Backend\UserController@deleteUser');
    Route::get('users/view-user/{id}', 'Backend\UserController@viewUserDetails');

    //-------------------CmsController--------------------------------------//
    Route::get('cms', 'Backend\CmsController@index');
    Route::get('cms/add-page', 'Backend\CmsController@addPage');
    Route::post('cms/save-page', 'Backend\CmsController@savePage');
    Route::get('cms/add-page/{id?}', 'Backend\CmsController@addPage');
    Route::get('cms/get-page/{id}', 'Backend\CmsController@getPageDetails');
    Route::post('cms/delete-page', 'Backend\CmsController@deletePage');


    //-------------------BannerController--------------------------------------//

    Route::get('banner', 'Backend\BannerController@getIndex');
    Route::get('banner/add-banner', 'Backend\BannerController@getAddBanner');
    Route::post('banner/add-banner', 'Backend\BannerController@postAddBanner');
    Route::get('banner/edit-banner/{id}', 'Backend\BannerController@getEditBanner');
    Route::post('banner/get-banner-detail', 'Backend\BannerController@getBannerDetail');
    Route::get('bannerData/', 'Backend\BannerController@getBanners');
    Route::get('banner/view-banner/{id}', 'Backend\BannerController@getBannerDetailView');
    Route::post('banner/delete-banner', 'Backend\BannerController@postDeleteBanner');

    //-------------------RatingController--------------------------------------//

    Route::get('rating', 'Backend\RatingController@index');
    Route::post('rating/update-points', 'Backend\RatingController@updatePoints');
    Route::get('rating/get-all-ratings', 'Backend\RatingController@getAllRatings');


    //------------------Password Controller----------------------------//
    Route::get('/password/email', 'AdminAuth\PasswordController@getEmail');
    Route::post('/password/email', 'AdminAuth\PasswordController@postEmail');
    Route::post('/password/reset', 'AdminAuth\PasswordController@reset');
    Route::get('/password/reset/{token?}', 'AdminAuth\PasswordController@showResetForm');

});
    //--------Routes for Backend End-----------//



    //--------Route for frontend-----------//
    Route::get('auth/logout', 'Auth\AuthController@logout');
    Route::get('auth/check-auth', 'Auth\AuthController@checkAuthentication');
    Route::get('activate/{id}', 'Frontend\HomeController@activateUser');

    /* Routes for home controller starts from here */

    Route::post('home/save-user', 'Frontend\HomeController@saveUser');
    /*Route::get('home/login','Frontend\HomeController@login');
    Route::get('home/forgot-pwd','Frontend\HomeController@forgotPwd');
    Route::get('home/sign-up','Frontend\HomeController@signup');
    ;*/
    Route::get('home/dir-pagination', 'Frontend\HomeController@dirPagination');
    /* Routes for home controller ends here*/
    Route::get('home/thankyou', 'Frontend\HomeController@thankyou');


    /*Routes for library Controller starts from here*/
    /* Route::get('library/new-forum','Frontend\LibraryController@addForumTopic');*/
    Route::get('library', 'Frontend\LibraryController@index');
    Route::get('library/forum-categories', 'Frontend\LibraryController@getCategories');
    Route::get('library/forum-sample', 'Frontend\LibraryController@sample');

    Route::post('library/add-new-forum', 'Frontend\LibraryController@addNewForum');
    Route::post('library/save-forum', 'Frontend\LibraryController@saveForum');

    Route::post('library/forum-listing', 'Frontend\LibraryController@forumListing');

    Route::get('library/forum-detail/{id}', 'Frontend\LibraryController@forumDetail');
    Route::post('library/get-forum-details', 'Frontend\LibraryController@getForumDetails');
    Route::post('library/add-comment', 'Frontend\LibraryController@addComment');
    Route::post('library/get-comments', 'Frontend\LibraryController@getComments');
    Route::post('library/update-forum-rating', 'Frontend\LibraryController@addForumRating');
    Route::get('library/update-forum-views/{id}', 'Frontend\LibraryController@updateViews');
    Route::post('library/home-forum-search', 'Frontend\LibraryController@homeForumSearch');
    Route::post('library/update-comment', 'Frontend\LibraryController@updateComment');
    Route::delete('library/remove-forum/{id}', 'Frontend\LibraryController@removeForum')->where('id', '[0-9]+');
    Route::delete('library/remove-comment/{id}', 'Frontend\LibraryController@removeComment')->where('id', '[0-9]+');
    /*Routes for library Controller ends here*/



    /*Routes for profile update/ About me Controller starts here*/
    Route::get('about-me/{user_id}', 'Frontend\ProfileController@index');
    Route::get('p/{id}', 'Frontend\ProfileController@profile');
    Route::any('user-info', 'Frontend\ProfileController@getUserInfo');
    Route::get('profile/basic-info/{id}', 'Frontend\ProfileController@getUserBasicInfo');
    Route::auth();

    Route::post('profile-image', 'Frontend\ProfileController@getProfileImage');
    Route::post('profile/update', 'Frontend\ProfileController@update');
    Route::post('upload-profile-photo','Frontend\ProfileController@uploadProfilePhoto');
    Route::post('save-cropped-profile-photo','Frontend\ProfileController@saveCroppedProfilePhoto');
    /*Routes for profile update/ About me Controller ends here*/

    /*Notes section starts from here*/
    Route::post('profile/get-notes', 'Frontend\NotesController@getNotes');
    Route::get('notes', 'Frontend\NotesController@notes');
    Route::post('notes/add-note', 'Frontend\NotesController@addNote');
    Route::get('notes/new-note-popup', 'Frontend\NotesController@getNewNote');
    /*Notes section ends here*/


    /*Routes for Photos and Albums section starts here*/
    Route::get('gallery/user-id/{user_id}', 'Frontend\AlbumsController@index');
    Route::get('get-gallery-items', 'Frontend\AlbumsController@getGalleryItems');
    Route::post('initialise-jquery-file-upload', 'Frontend\AlbumsController@initialiseJqueryFileUpload');
    Route::delete('initialise-jquery-file-upload', 'Frontend\AlbumsController@initialiseJqueryFileUpload');
    Route::get('remove-temp-folders-of-albums', 'Frontend\AlbumsController@removeTempFolderOfAlbums');
    Route::get('remove-temp-folders-of-videos', 'Frontend\VideosController@removeTempFolderOfvideos');
    Route::post('post-album', 'Frontend\AlbumsController@postAlbum');
    Route::get('user-albums', 'Frontend\AlbumsController@getUsersAlbums');
    Route::get('photos', 'Frontend\AlbumsController@getAlbumsPhotos');
    Route::get('album/id/{id}/user-id/{user_id}', 'Frontend\AlbumsController@albumDetail');//Album detail page routes
    Route::get('get-albums-photos', 'Frontend\AlbumsController@getAlbumsPhotos');
    Route::get('delete-album', 'Frontend\AlbumsController@deleteAlbum');
    Route::get('delete-photo', 'Frontend\AlbumsController@deletePhoto');
    Route::any('initialise-jquery-file-upload-add-photos-to-album',
        'Frontend\AlbumsController@initialiseJqueryFileUploadPhotosToAlbum');
    Route::post('update-album-detail', 'Frontend\AlbumsController@updateAlbumDetail');
    /*Routes for Photos and Albums section ends here*/

    /*Routes for Videos section starts here*/
    Route::post('initialise-jquery-file-upload-for-videos',
        'Frontend\VideosController@initialiseJqueryFileUploadForVideos');
    Route::post('post-photos', 'Frontend\AlbumsController@postPhotosToAlbum');
    Route::post('post-videos', 'Frontend\VideosController@postVideos');
    Route::get('last-video-thumb-and-count', 'Frontend\VideosController@getUsersVideoLastThumbNCount');
    Route::get('videos/user-id/{user_id}', 'Frontend\VideosController@Videos');
    Route::get('get-videos-listing', 'Frontend\VideosController@getUsersVideosListing');
    Route::get('delete-video', 'Frontend\VideosController@deleteVideo');
    Route::get('play-video/{video_id}', 'Frontend\VideosController@renderVideoPopup');
    /*Routes for Videos section ends here*/

    /*Routes for User Follow section*/
    Route::post('follower/add', 'Frontend\FollowController@addfollower');
    Route::post('follower/remove', 'Frontend\FollowController@removefollower');
    Route::post('follower/getfollowed', 'Frontend\FollowController@getFollowed');
    Route::get('following/user-id/{user_id}', 'Frontend\FollowController@index');
    Route::post('following-details', 'Frontend\FollowController@getFollowingDetails');
    Route::post('following-search', 'Frontend\FollowController@searchUsers');

    /*Routes for News feed section*/
    Route::get('newsfeed/user-id/{user_id}', 'Frontend\NewsFeedController@index');
    Route::post('user-wall-posts', 'Frontend\NewsFeedController@getUserWallpost');
    Route::post('post-text-on-wall', 'Frontend\NewsFeedController@addTextWallpost');
    Route::post('post-photos-on-wall', 'Frontend\NewsFeedController@addPhotosWallpost');
    Route::post('post-videos-on-wall', 'Frontend\NewsFeedController@addVideoWallpost');
    Route::any('remove-temp-folders-of-wallposts', 'Frontend\NewsFeedController@removeTempFoldersOfWallposts');
    Route::any('add-photos-to-wallpost','Frontend\NewsFeedController@initialiseJqueryFileUploadPhotosUpdate');
    Route::any('add-videos-to-wallpost','Frontend\NewsFeedController@initialiseJqueryFileUploadVideoUpdate');
    Route::post('like-wall-post', 'Frontend\NewsFeedController@likeWallPost');
    Route::post('unlike-wall-post', 'Frontend\NewsFeedController@unlikeWallPost');
    Route::post('post-comment-on-wall', 'Frontend\NewsFeedController@addCommentToWallPost');



    /* Notification section */
    Route::get('get-notifications', 'Frontend\NotificationsController@getNotifications');
    Route::get('delete-notifications', 'Frontend\NotificationsController@deleteNotification');
    Route::get('notifications', 'Frontend\NotificationsController@index');

    /* Local search Routes */
    Route::get('get-cities-by-state/{id}', 'Frontend\LocalSearchController@getCitiesByStateId');
    Route::get('get-states', 'Frontend\LocalSearchController@getAllStates');
    Route::get('get-cities/{id}', 'Frontend\LocalSearchController@getCitiesByStateId');
    Route::post('locals/search', 'Frontend\LocalSearchController@getLocals');
    /* Local search Routes ENDS*/

    /* Error Controller Routes */
    Route::any('page-not-found', 'Frontend\ErrorController@pageNotFound');



/*    Route::get('/pusher', function() {
        event(new App\Events\HelloPusherEvent('hi simer'));
         return "Event has been sent!";

    });*/
