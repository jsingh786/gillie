/**
 * Created by hkaur5 on 9/16/2016.
 */

gillieNetFrontApp.controller('albumsAndVideosCtrl',['$scope','ngDialog','CSRF_TOKEN','$http','$timeout','flash','$rootScope', function($scope,ngDialog,CSRF_TOKEN,$http, $timeout,flash, $rootScope) {


    $rootScope.loading = false;




    //When Page unloads then delete data from temp folder.
    $( window ).unload(function() {
        if( $("table[role=presentation] div.template-download").length > 0 )
        {
            $.ajax(
                {
                    async: false,
                    url : PUBLIC_PATH+"remove-temp-folders-of-albums"
                });

            $.ajax(
                {
                    async: false,
                    url : PUBLIC_PATH+"remove-temp-folders-of-videos"
                });

        };
        //Remove div where blueimp thumbnails are created on refreshing the page.
        $('div.template-download').remove();
    });


    angular.element(document).ready(function ()
    {
        //Declarations
        $scope.get_albums = {}; // Array for storing params for ajax call to get albums listing.
        $scope.albums_exists  = false; // Make it true if albums exist (albums container).
        $scope.no_albums = false; // Make it true if albums does not exist to show no albums message.
        $scope.get_albums.offset = 0; // offset for get albums listing.
        $scope.get_albums.limit = 10;// limit for get albums listing.
        $scope.get_albums.is_more_records = 0 ; //This param checks if
        $scope.albums = []; // Array to store albums listing to be displayed.
        $scope.loadAlbums(1); // call function to get albums.
        $scope.videos = {}; // Array to store videos listing data.
        $scope.delete_btn = false; // Delete btn on albums thumbnail.
        $scope.videos_exists = false; // Make this variable true if videos exist else false.


    });

    /**
     * Load albums and video tile if videos exist using
     * ajax
     * @author hkaur5
     * @param integer first_time_loading (1/0) if 1 then
     * in case no albums or videos exist then show "No albums or videos added"
     */
    $scope.loadAlbums = function (first_time_loading) {

        $scope.loading_photos = true;
        $http({
            url: PUBLIC_PATH+'get-gallery-items',
            method: "GET",
            params: {'offset': $scope.get_albums.offset,
                'limit': $scope.get_albums.limit,
                'get_videos': first_time_loading,
                'userId': $('input#userId').val(),
            }
        }).success(function(response) {
            $scope.loading_photos = false;

            if(response['albums'] || response['video_tile_data'])
            {
                //Hide Loader.
            }
            else if(!response['albums'] && !response['video_tile_data'])
            {
                //Hide Loader.

                //If function is called on document.ready i.e it is loaded
                //first time then in case of no records show "no albums or video added"
                //message.
                if(first_time_loading)
                {

                    $scope.no_albums = true;
                }

                return;
            }

            if(response['albums'])
            {

                $scope.no_albums = false;
                angular.forEach(response.albums, function(value, key) {
                    $scope.albums.push({'display_name':value.display_name,'photo_count':value.photo_count,
                        'last_photo_path':value.last_photo_path,'id':value.id})
                });

                //Update offset to "old limit + old offset".
                $scope.get_albums.offset = parseInt( $scope.get_albums.offset)+parseInt( $scope.get_albums.limit);
                
                //On basis of this scope run ajax call. If 0 then don't run ajax to get more albums.
                if(response.is_more_records > 0)
                {
                    $scope.get_albums.is_more_records = response.is_more_records;
                }
                else {
                    $scope.get_albums.is_more_records = 0;
                }
                //If function is called on document.ready i.e
                //first time then then ng-show albums_exists.
                if(first_time_loading = 1)
                {
                    $scope.albums_exists  = true;

                }
            }
            if(first_time_loading)
            {
                if(response['video_tile_data'])
                {
                    $scope.videos_exists = true;
                    $scope.videos.last_thumb_path = response['video_tile_data']['last_video_thumb_path']
                    $scope.videos.count = response['video_tile_data']['count']
                }
            }

        });
    }


    /**
     * Show Add album popup and hide
     * add videos popup
     * @author hkaur5
     */
    $scope.showCreateAlbumPopup = function()
    {
        $('#add_album').toggle();
        $('#add_videos').hide();
    }


    /**
     * Show Add Video popup and hide
     * add albums popup
     * @author hkaur5
     */
    $scope.showAddVideosPopup = function()
    {
        $('#add_videos').toggle();
        $('#add_album').hide();
    }



    /**
     * Loading more albums on touching bottom
     * Call Load more albums function when user
     * scroll to bottom of window.
     * @author hkaur5
     */
    window.scrollTo(0,0);
    $(window).scroll(function() {

        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            //  console.log($scope.get_albums.is_more_records);
            if($scope.get_albums.is_more_records > 0)
            {

                $scope.loadAlbums(0);
            }
        }
    });



    /**
     * Submit Album data using ajax call
     * and renders album(last photo thumb) after
     * success
     * @author hkaur5
     * @returns {boolean}
     */
    $scope.postAlbum = function()
    {

        var album_name = $("input#album_name").val();
        var album_name_trimmed = album_name.trim();
        if(album_name_trimmed == "")
        {
            //alert(constants['your_album_is_untitled']);
            flash.pop({title: 'Error', body: constants['your_album_is_untitled'], type: 'error'});
            return false;
        }
        else if ($("input#album_name").val().length > 30 )
        {
            //alert( constants['album_name_should_be_no_more_than_30_characters']);
            flash.pop({title: 'Error', body: constants['album_name_should_be_no_more_than_30_characters'], type: 'error'});
            return false;
        }
        if( $("table[role=presentation] div.template-download span.preview img").length > 0 )
        {
            //Disable button and change text.
            $('#post_photos').attr('disabled',true);
            $('#post_photos').text('POSTING...');

            $http.post(PUBLIC_PATH+'post-album',{'album_name':album_name_trimmed})

                .success(function(response) {

                    //Scroll top of album section.
                    $('html, body').animate({
                        scrollTop: $('.bg').offset().top - 20
                    }, 'slow');

                    //Enable button.
                    $('#post_photos').attr('disabled',false);
                    $('#post_photos').text('POST PHOTOS');
                    if(response.success == 1)
                    {
                        $scope.albums.push({'display_name':response.display_name,'photo_count':response.photo_count,
                            'last_photo_path':response.last_photo_path,'id':response.id})
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        $('#add_album').hide();// hide popup

                        flash.pop({title: 'Success', body: constants['album_saved'], type: 'success'});
                        $("input#album_name").val('');// empty album name input
                        $scope.albums_exists  = true; // show div which contains albums (ng-repeat)
                        $scope.no_albums = false; // hide "No albums added" message.
                        // After adding an album increment offset so that load more photos work accordingly.
                        $scope.get_albums.offset = parseInt( $scope.get_albums.offset)+parseInt(1);

                    }
                    else
                    {
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        $('#add_album').hide();// hide popup
                        flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'success'});
                        $("input#album_name").val('');// empty album name input
                    }
                });
        }
        else
        {
            flash.pop({title: 'Error', body: constants['you_have_added_no_photos_to_post'], type: 'error'});
        }
    }

    /**
     * Show dialog box to confirm album deletion
     * @param id (album id)
     * @param item ( item to be deleted from albums array)
     */
    $scope.confirmAlbumDelete = function(id, item)
    {
        showDialogMsg( 'Confirm', "Are you sure you want to delete this album?", 0,
            {
                buttons: [
                    {
                        text: "Cancel",
                        click: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "Delete",

                        click: function() {
                            $( this ).dialog( "close" );
                            $scope.deleteAlbum(id, item);
                        }
                    }
                ],
                show: {
                    effect: "fade"
                },
                hide: {
                    effect: "fade"
                },
                dialogClass: "general_dialog_message",
                height: 150,
                width: 300
            });
    }
    /**
     * Delete Album using ajax call
     * @author hkaur5
     */

        $scope.deleteAlbum = function (id, item) {

            //Index of album in albums array.
            var index = $scope.albums.indexOf(item);

            // Change opacity of album tile to show progress of delete
            // action.
            $('#gallery_block_'+id).css('opacity','0.5');
            $http({
                url: PUBLIC_PATH+'delete-album',
                method: "GET",
                params: {
                    'id': id,
                }
            }).success(function (response) {
                if(response)
                {

                    //Remove item from albums array.
                    $scope.albums.splice(index, 1);

                    //Store back opacity of album tile.
                    $('#gallery_block_'+id).css('opacity','1');
                  //  $('#gallery_block_'+id).hide();
                    flash.pop({title: 'Success', body: constants['album_deleted'], type: 'success'});
                }
                else
                {
                    $('#gallery_block_'+id).css('opacity','1');
                    flash.pop({title: 'Success', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
                }
            });
    }


    /**
     * Post Video using Ajax and
     * Render video (video thumb or update last added video thumb)
     * on video tile.
     * @author hkaur5
     *
     */
    $scope.postVideos = function()
    {


        if( $("table[rel=presentation_videos] div.template-download span.preview label").length > 0 )
        {
            //Disable button and change text.
            $('#post_videos').attr('disabled',true);
            $('#post_videos').text('POSTING...');

            $http.post(PUBLIC_PATH+'post-videos')
                .success(function(response) {
                    
                    //Scroll top of album section.
                    $('html, body').animate({
                        scrollTop: $('.bg').offset().top - 20
                    }, 'slow');

                    $scope.no_albums = false;
                    //Enable button
                    $('#post_videos').attr('disabled',false);
                    $('#post_videos').text('POST VIDEOS');

                    console.log(response['success']);
                    if(response['success'] ==1 )
                    {
                        flash.pop({title: 'Success', body: constants['videos_added'], type: 'success'});
                        $scope.videos_exists = true;
                        $scope.videos.last_thumb_path = response['last_video_thumb_path']
                        $scope.videos.count = response['count']
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        $('#add_videos').hide();

                    }
                    else
                    {
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
                        $('#add_videos').hide();
                    }
                    
                });
        }
        else 
        {
            flash.pop({title: 'Error', body: constants['you_have_added_no_videos_to_post'], type: 'error'});
        }
    }


}]);
