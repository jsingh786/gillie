/**
 * Created by hkaur5 on 9/19/2016.
 */
gillieNetFrontApp.controller('albumDetailController',['$scope','ngDialog','CSRF_TOKEN','$http','$window','$timeout','flash','$rootScope', function($scope,ngDialog,CSRF_TOKEN,$http, $window, $timeout, flash, $rootScope) {

    $rootScope.loading= false;

    angular.element(document).ready(function ()
    {
        //Declarations.
        $scope.loading_photos = true; // Loader for photos listing.
        $scope.photos_data = []; // Array for storing params for get photos listing ajax.
        $scope.edit_album_name = false; // Make it true when to show input type for editing album
        $scope.photos_data.offset = 0; // Offset for get photos listing.
        $scope.photos_data.limit = 20; // Limit for get photos listing.
        $scope.photos_data.is_more_records = 0 ;//Param to check if more photos exist.
        $scope.photos = []; // Array to store photos listing data.
        $scope.loadPhotos(); // Call function to load photos.

        //Bring focus to textbox end when user click on album title.
        $('h1#album_name').click(function()
        {
            //Bring focus to edit album input.
            var el = $("input#edit_album_name:text").get(0);
            var elemLen = el.value.length;

            el.selectionStart = elemLen;
            el.selectionEnd = elemLen;
            el.focus();
        });

    });

    //When Page unloads then delete data from temp folder.
    $( window ).unload(function() {
        if( $("table[role=presentation] div.template-download").length > 0 )
        {
            $.ajax(
                {
                    async: false,
                    url : PUBLIC_PATH+"remove-temp-folders-of-albums"
                });

        };
        //Remove div where blueimp thumbnails are created on refreshing the page.
        $('div.template-download').remove();
    });



    /**
     * load photos and render
     * photo thumb using photos scope variable
     * (pushing data to photos $scope.)
     *
     * @author hkaur5
     */
    $scope.loadPhotos = function () {
        $scope.loading_photos = true;
        $http({
            url: PUBLIC_PATH+'get-albums-photos',
            method: "GET",
            params: {'offset': $scope.photos_data.offset,
                'limit': $scope.photos_data.limit,
                'album_id':$('input#album_id').val()
            }
        }).success(function(response) {
            $scope.loading_photos = false;
            $scope.album_name = response.album_display_name;
            if(response['photos'])
            {
                console.log(response['photos']);
                angular.forEach(response.photos, function(value, key) {
                    $scope.photos.push({'name':value.name,'path':value.path,
                                        'id':value.id,
                                        'path_popup_thumbnail':value.path_popup_thumbnail,
                                        'album_posted_by':value.album_posted_by})
                });

                //Update offset to "old limit + old offset".
                $scope.photos_data.offset = parseInt( $scope.photos_data.offset)+parseInt( $scope.photos_data.limit);

                //On basis of this scope run ajax call. If 0 then don't run ajax to get more photos.
                if(response['is_more_records'] > 0)
                {
                    $scope.photos_data.is_more_records = response['is_more_records'];
                }
                else {
                    $scope.photos_data.is_more_records = 0;
                }

            }

            else
            {
                //Do Nothing.
            }

        });
    }


    //Loading more photos on touching bottom of web page.
    //Call LoadPhotos function.
    window.scrollTo(0,0);
    $(window).scroll(function() {

        if($(window).scrollTop() + $(window).height() == $(document).height()) {

            if($scope.photos_data.is_more_records)
            {
                $scope.loadPhotos();
            }
        }
    });

    /**
     * showing confirm box to delete photo
     *
     * @param id (photo id)
     * @param item ( item to be deleted from photos array)
     */
    $scope.confirmPhotoDelete = function(id, item){
        showDialogMsg( 'Confirm', "Are you sure you want to delete this photo?.", 0,
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
                            $scope.deletePhoto(id, item);
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
    }

    /**
     * Delete Photo
     * @param object elem (Photo tile)
     * @param integer id (photo id)
     * @author hkaur5
     */
    $scope.deletePhoto = function ( id, item) {

        var index = $scope.photos.indexOf(item);

        //Change opacity of photo tile to show progress.
        $('#photo_block_'+id).css('opacity','0.5');
        $http({
            url: PUBLIC_PATH+ 'delete-photo',
            method: "GET",
            params: {
                'id': id,
            }
        }).success(function (response) {

            if(response.success)
            {
                //Change opacity back before removing photo tile.
                $('#photo_block_'+id).css('opacity','1');

                //Removing element from photos array.
                $scope.photos.splice(index, 1);

                flash.pop({title: 'Success', body: constants['photo_deleted'], type: 'success'});
                if(response.last_photo)
                {
                    $window.location.href = PUBLIC_PATH+'/gallery/user-id'+$('#logged_in_userId');
                }

            }
            else
            {
                $('#photo_block_'+id).css('opacity','1');
                flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
            }

            console.log(response);
        });
    }



    /**
     * Show Add Photo Popup and trigger add photos
     * input click.
     * @author hkaur5
     */
    $scope.showAddMorePhotosPopup = function()
    {
        if($('#add_more_photos').css('display') == "none"){
            $('#add_more_photos').show();
            $('input#add_photos').trigger('click');
        }
        else{
            $('#add_more_photos').hide();
        }

    }


    /**
     * Submit Photos Data using ajax call
     * and Render photo thumb
     * @author hkaur5
     */
    $scope.postPhotos = function()
    {
        if( $("table[role=presentation] div.template-download span.preview img").length > 0 ) {
            //Disable button and change text.
            $('#post_photos').attr('disabled',true);
            $('#post_photos').text('POSTING...');
            $http.post(PUBLIC_PATH + 'post-photos', {'album_id': $("input#album_id").val()})
                .success(function (response) {
                    //Scroll top of photos section.
                    $('html, body').animate({
                        scrollTop: $('.bg').offset().top - 20
                    }, 'slow');


                    //Disable button and change text.
                    $('#post_photos').attr('disabled',false);
                    $('#post_photos').text('POST PHOTOS');
                    if (response.success == 1) {
                        angular.forEach(response.photos, function (value, key) {
                            $scope.photos.push({
                                'name': value.name, 'path': value.path,
                                'id': value.id, 'path_popup_thumbnail': value.path_popup_thumbnail
                            })
                        });
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        $('#add_more_photos').hide();// hide popup
                        flash.pop({title: 'Success', body: constants['photos_added_to_the_album'], type: 'success'});// show msg
                        $scope.photos_data.offset = parseInt($scope.photos_data.offset) + parseInt(response.photos_count);

                    }
                    else {
                        $('div.template-download').remove();// Remove blue imp presentation template.
                        $('#add_more_photos').hide();// hide popup
                        flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});// show msg
                    }
                });
        }
        else
        {
            flash.pop({title: 'Error', body: constants['you_have_added_no_photos_to_post'], type: 'error'});
        }
    }



    /**
     * Show Image in ng-dialog popup using plain html
     * @author hkaur5
     * @param string path (photo path)
     */
    $scope.showImagePopup = function (path) {

        var html = '';
        html += '<div class="title_popup" id="image_popup">';
            html += '<a class="popup_close_btn" href="javascript:;" ng-click="ngDialog.close()">X</a>';
            html += '<div class="">';
                html +='<img class="img_popup" style="max-width:600px; max-height:600px;" src="'+PUBLIC_PATH+'/image.php?width=600&height=600&image='+path+'">';
            html += '</div>';
        html += '</div>';

        $scope.ngDialog = ngDialog;
        $('span#loading_photos').hide();
        ngDialog.open({
            scope:$scope,
            template:html,
            plain: true,
            //className: 'ngdialog-theme-default',
            closeByDocument: true
            //backdrop : 'static'
        });
    }


    /**
     * If new value of album name is set in text box
     * then it saves album name with an ajax call
     * and show sucecss msg else nothing happens.
     * @param integer id(album id)
     * @author hkaur5
     */
    $scope.saveAlbumName = function(id)
    {

        var album_name = $('#edit_album_name').val();
        var album_name_trimmed = album_name.trim();

        if(album_name_trimmed != '' && $('#edit_album_name').val() != $scope.album_name)
        {

           $('#album_name').text(album_name_trimmed);
            $http.post(PUBLIC_PATH+'update-album-detail',{'id':id,'album_name':$('#edit_album_name').val()})
                .success(function(response) {
                    console.log(response);
                    if(response.success)
                    {
                        $scope.album_name = response.album_display_name;
                        flash.pop({title: 'Success', body: constants['album_name_updated'], type: 'success'})
                    }
                    else
                    {
                        $('#edit_album_name').val($scope.album_name);
                        flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'})
                    }

                });
        }
        else if($('#edit_album_name').val() == $scope.album_name)
        {
            // Do nothing.
        }
        else
        {
            var album_name = $scope.album_name;
            $('#edit_album_name').val(album_name);
        }
        if(album_name_trimmed == '')
        {
            flash.pop({title: 'Error', body: constants['album_name_cannot_be_empty'], type: 'error'})
        }
       $scope.edit_album_name = false;//Set edit_album_name false so that textbox hides.

    }

    //To be used later!
    /*$scope.$watch('abcd', function(val) {
        if(val === 'One') {
            doWhatever();
        }
    });*/

}]);