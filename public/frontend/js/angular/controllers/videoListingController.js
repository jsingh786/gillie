/**
 * Created by hkaur5 on 10/4/2016.
 */
gillieNetFrontApp.controller('videosListingCtrl',['$scope','ngDialog','CSRF_TOKEN','$http','$timeout','flash','$rootScope', function($scope,ngDialog,CSRF_TOKEN,$http, $timeout, flash, $rootScope) {

    $rootScope.loading = false;
    angular.element(document).ready(function ()
    {
        $scope.get_videos = {}; //Array stores params for get video listing.
        $scope.get_videos.offset = 0; //offset to get video listing.
        $scope.get_videos.limit = 20; //Limit to get video listing.
        $scope.no_videos = false; //When no video available then make it true to show no video available msg.
        $scope.get_videos.is_more_records = 0 ; //param to check if more videos exist after getting video listing.
        $scope.videos = []; //Array tpo store videos data for lisy
        $scope.loadVideos(1);

        //Set time out as delete span elements appear in dom after completion of ajax call.
        $timeout(function () {

            //Delete Video on click of delete span.
            $(".gallery").on('click','span.delete_btn_span',function()
            {
                var elem = this;
                showDialogMsg( 'Confirm', "Are you sure you want to delete this video?", 0,
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
                               console.log(elem);
                                console.log('obj:'+$(elem).attr('rel'));
                                $scope.deleteVideo(elem, $(elem).attr('rel'));
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
                }
                );
            });
        }, 2000);
    })

    /**
     * Load Videos by making an http call
     * and push elements to Videos scope to
     * render videos using ng-repeat.
     * @author hkaur5
     */
    $scope.loadVideos = function (first_time_loading) {
        $scope.loading_photos = true;
    
        $http({
            url: PUBLIC_PATH+'get-videos-listing',
            method: "GET",
            params: {'offset': $scope.get_videos.offset,
                'limit': $scope.get_videos.limit,
                'userId':$('input#userId').val()
            }
        }).success(function(response) {
            $scope.loading_photos = false;
            if(response['videos'])
            {
                $scope.no_videos = false;
                angular.forEach(response['videos'], function(value, key) {
                    $scope.videos.push({'thumb_path':value.video_thumb_path,'id':value.id,
                        'path':value.video_path,'name':value.name})
                });

                $scope.get_videos.is_more_records = response['is_more_records'];

                console.log($scope.get_videos.is_more_records);
                $scope.get_videos.offset = parseInt($scope.get_videos.offset) + parseInt($scope.get_videos.limit);
            }
            else
            {
                $scope.get_videos.is_more_records = 0;
                //Do nothing
            }
            if(first_time_loading && ! response['videos'])
            {
                $scope.no_videos = true;
            }

        });
    }

    //Loading more albums on touching bottom.
    window.scrollTo(0,0);
    $(window).scroll(function() {

        if($(window).scrollTop() + $(window).height() == $(document).height()) {

            //If more video recors exists -- according to previous ajax call.
            if($scope.get_videos.is_more_records > 0)
            {
                $scope.loadVideos();
            }
        }
    });



    /**
     * Delete Video.
     * Ajax Call to Delete video and showing failure or success
     * msgs accordingly.
     * @param elem
     * @param video_id
     * @author hkaur5
     */
    $scope.deleteVideo = function(elem, video_id)
    {
        //Change opacity of photo tile to show progress.
        $('#video_block_'+video_id).css('opacity','0.5');
        $http({
            url: PUBLIC_PATH+'delete-video',
            method: "GET",
            params: {'id': video_id,
            }
        }).success(function(response) {
            $('#video_block_'+video_id).css('opacity','1');
            if(response == 1 || response == 2)// 1 = all went well, 2 = files not deleted.
            {
                $('#video_block_'+video_id).remove();
                flash.pop({title: 'Success', body: constants['video_deleted'], type: 'success'});
            }
            else if(response == 3)
            {
                flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
            }
            else if(response == 4)
            {
                $('#video_block_'+video_id).remove();
                flash.pop({title: 'Success', body: constants['video_deleted'], type: 'success'});
                flash.pop({title: 'Warning', body: constants['video_thumbnails_not_deleted'], type: 'warning'});

            }
        });
    }

    /**
     * create popup using ng-dialog
     * to play video in popup.
     * @param string video_path (path of video to be played)
     * @author hkaur5
     * @author jsingh7 (Added event listener)
     */
    $scope.showVideoPlayerPopup = function(video_path)
    {
        //Creating ng-dialog for above html to play video.
        $scope.ngDialog  = ngDialog;
        ngDialog.open({
            animation: true,
            scope:$scope,
            template: '<div id="video_popup" class="title_popup"><div id="video_player"></div></div>',
            plain: true,
            //className: 'ngdialog-theme-default',
            closeByDocument: true
            //backdrop : 'static'
        });

        //Call jwplayer when ng-dialog is opened.
        $scope.$on('ngDialog.opened', function (e, $dialog) {
            jwplayer("video_player").setup({
                file: video_path,
                width: "600px",
                height: "600px",
                stretching: "bestfit",
            });
        });
        $('#video_popup').addClass('title_popup');

    }
    
}]);




