gillieNetFrontApp.controller('libraryDetailController', ['$scope', 'CSRF_TOKEN', '$http', '$rootScope', 'flash',
    'ngDialog','$timeout',
    function ($scope, CSRF_TOKEN, $http, $rootScope, flash, ngDialog,$timeout) {

    //initialise scope variables
    $scope.forum_detail = {};
    $scope.forum_comment = {};
    $scope.forum_comments = [];
    $scope.forum_comments.is_more_records = 0;
    //$scope.counter = 0;

    $scope.checked = false;
    $scope.loading_comm = false;
    $scope.offset = 0;
   // $scope.starRating = 1;

    $scope.showCommentEditBox = false;


    $scope.hoverRating1 = $scope.hoverRating2 = $scope.hoverRating3 = 0;

    //onload get id of forum, fetch its details
    angular.element(document).ready(function(){
        var pathArray = window.location.pathname.split('/');
       // var forum_id = pathArray[5];
        var forum_id = $('input#forum_id').val();
        if (forum_id) {
            $rootScope.loading = true;
            $scope.getForumDetail(forum_id)
            $scope.getForumComments(forum_id);
            $scope.updateForumViews(forum_id);
        }

    });

    //onload function to fetch details of forum
    $scope.getForumDetail = function (forum_id) {

        var data = {'forum_id':forum_id};
        var url_path = PUBLIC_PATH+'library/get-forum-details';
        $scope.starRating = $http.post(url_path,data).success(function(response){
            $rootScope.loading = false;

            //console.log(response['forum_detail']['description']);
          //  $('p#forum_description').html(response['forum_detail']['description'])
            $scope.forum_detail = response.forum_detail;
            //$scope.starRating = response.forum_detail.stars;
            $scope.starRating = response.forum_detail.stars;
            return $scope.starRating;
            
        }).error(function(){

        })
    }


    $scope.updateForumViews = function(forum_id){
        $rootScope.loading = false;

        var url_path = PUBLIC_PATH+'library/update-forum-views/'+forum_id;
        $http.get(url_path).success(function(){

        }).error(function(){

        });
    }


    //onload function to get forum comments
    $scope.getForumComments = function (forum_id) {
        $scope.loading_comments = true;
        
        var data = {'forum_id':forum_id};
        var url_path = PUBLIC_PATH+'library/get-comments';
        $http.post(url_path,data).success(function(response){
            $scope.loading_comments = false;
            $scope.forum_comments = response;

        }).error(function(){

        })
    }

    // add comment on forum click
    $scope.addCmtClk = function(forum_id)
    {
        //$rootScope.loading = true;
        $('button#add_comment.gillie-btn.add_comment').text(constants['adding']);
        $scope.checked = true;
        $scope.loading_comm = true;
        flash.pop({title: 'Information', body: constants['adding'], type: 'info'});
        var url_path = PUBLIC_PATH+'library/add-comment';
        $scope.forum_comment.forum_id = forum_id;
        var data = $scope.forum_comment;
        $http.post(url_path,data).success(function(response){

            //$rootScope.loading = false;

            if(response.status == 301) {
                flash.pop({title: "Error", body: response.message, type: "error"});
                return false;
            }

            //increment offset value if new comment is added
            $scope.offset = parseInt($scope.offset+1);

            $scope.forum_comment = '';
          
            $scope.forum_comments.comments.unshift(response);
            $scope.checked = false;

            $scope.loading_comm = false;

            $('button#add_comment.gillie-btn.add_comment').text(constants['add_comment']);
            $('div#toast-container div.toast-info').fadeOut(300, function () {
                flash.pop({title: 'Success', body: constants['added'], type: 'success'});
            });

        }).error(function(response){
            //$rootScope.loading = false;
            //disabled false
            $scope.checked = false;
            //remove loading text
            $scope.loading_comm = false;

            $('button#add_comment.gillie-btn.add_comment').text(constants['add_comment']);
            $('div#toast-container div.toast-info').fadeOut(300, function () {
                flash.pop({
                    title: "Error",
                    body: constants['oops_some_error_occurred_please_try_again'],
                    type: "error"
                });
            });
        });
    }

    //show more button to load more comments
    $scope.showMore = function(forumId)
    {
        $rootScope.loading = true;
        $scope.offset = parseInt($scope.offset + $scope.limit);
        var data = {'forum_id':forumId,'offset': $scope.offset};
        var url_path = PUBLIC_PATH+'library/get-comments';
        $http.post(url_path,data).success(function(response){

            $scope.forum_comments.is_more_records = response.is_more_records;
           $rootScope.loading = false;
            if(response.comments.length > 0) {
                $rootScope.loading = false;
                 var i;
                    for (i = 0; i < response.comments.length ;i++) {
                        $scope.forum_comments.comments.push(response.comments[i]);

                    }
                console.log($scope.forum_comments.is_more_records);
            }
            else  if(response.comments.length == 0)
            {
                $rootScope.loading = false;
                $scope.hide_view_more = true;
            }
        }).error(function(){

        })
    }

    //click of rating button
    $scope.click3 = function (param,forumId) {

        $rootScope.loading = true;
        var url_path =  PUBLIC_PATH+'library/update-forum-rating';
        var data ={'rating':param,'forum_id':forumId};
        $http.post(url_path,data).success(function(response){
            $rootScope.loading = false;
            if(response.status == 200) {
                $scope.starRating = response.avg_rating;
                flash.pop({title: 'Success', body: 'Rating submitted.', type: 'success'});
            }
            else if(response.status == 301)
            {
                flash.pop({title: "Error", body: response.message, type: "error"});
            }
        }).error(function() {
            flash.pop({title: "Error", body: "Oops Please Try again", type: "error"});
        });
    };

    /**
     * Opens a popup to edit forum.
     * It also fills up the form with data.
     *
     * @author jsingh7
     * @version 1.0
     */
    $scope.editForumPopup = function()
    {
        $scope.ngDialog = ngDialog;
        ngDialog.open({
            animation       : true,
            scope           : $scope,
            template        : 'editForumTemplate',
            controller      : 'editForumController',
            closeByDocument : false
        });
    }


        $scope.confirmForumDelete = function(forumId)
        {
            showDialogMsg( 'Confirm', "Are you sure you want to delete ?", 0,
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
                                $scope.deleteForum(forumId);
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
        
        
    $scope.deleteForum = function(forumId)
    {
        flash.pop({title: 'Information', body: constants['removing'], type: 'info'});
        $http({
            method: 'DELETE',
            url: PUBLIC_PATH + "library/remove-forum/"+forumId,
            responseType: "JSON"
        })
        .then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            if(response.data == '1') {
                //Flash the message.
                $('div#toast-container div.toast-info').fadeOut(300, function () {
                    flash.pop({title: 'Success', body: constants['removed'], type: 'success'});
                    setTimeout(function () {
                        window.location.replace(PUBLIC_PATH + "library")
                    }, 3000);
                });
            } else {
                //Flash the error message.
                $('div#toast-container div.toast-info').fadeOut(300, function () {
                    flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'],
                        type: 'error'});
                });
            }
        }, function errorCallback(response) {

            //Flash message.
            flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
        });
    }
    $scope.showCommentEditBoxAndSaveBtn = function()
    {
        $scope.showCommentEditBox = true;
        $scope.showSaveCommentBtn = true;
        $scope.showEditCommentBtn = false;
    }
        /**
         *
         * @param integer commentId
         * @param ul item
         */
    $scope.removeComment = function(commentId, item)
    {
        flash.pop({title: 'Information', body: constants['removing'], type: 'info'});
        $http({
            method: 'DELETE',
            url: PUBLIC_PATH + "library/remove-comment/"+commentId,
            responseType: "JSON"
        })
            .then(function successCallback(response) {
                // this callback will be called asynchronously
                // when the response is available
                if(response.data == '1') {
                    //Flash the message.
                    $('div#toast-container div.toast-info').fadeOut(300, function () {
                        flash.pop({title: 'Success', body: constants['removed'], type: 'success'});
                    });

                    var index = $scope.forum_comments.comments.indexOf(item);
                    $scope.forum_comments.comments.splice(index, 1);

                } else {
                    //Flash the error message.
                    $('div#toast-container div.toast-info').fadeOut(300, function () {
                        flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'],
                            type: 'error'});
                    });
                }
            }, function errorCallback(response) {

                //Flash message.
                flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
            });
    }

        //On click of edit button of comment.
        showCommentEditControls = function(elem) {
            $(elem).hide();
            $('div.library_comments a#commentSaveBtn_'+$(elem).attr('rel')).show();
            $('div.library_comments textarea#commentEditBox_'+$(elem).attr('rel')).show();
            $('div.library_comments p#commentViewBox_'+$(elem).attr('rel')).hide();
        }

        //On click of save button of comment.
        saveComment = function(elem) {
            flash.pop({title: 'Information', body: constants['updating'], type: 'info'});

            $http({
                method: 'POST',
                url: PUBLIC_PATH + "library/update-comment",
                data: {
                    'comment_text': $('div.library_comments textarea#commentEditBox_'+$(elem).attr('rel')).val(),
                    'comment_id': $(elem).attr('rel')
                },
                responseType: "JSON"
            })
                .then(function successCallback(response) {
                    // this callback will be called asynchronously
                    // when the response is available
                    if(response.data == '1') {
                        //Flash the message.
                        $('div#toast-container div.toast-info').fadeOut(300, function () {
                            flash.pop({title: 'Success', body: constants['updated'], type: 'success'});
                        });

                    } else if (response.data == '0'){
                        //Flash the error message.
                        $('div#toast-container div.toast-info').fadeOut(300, function () {
                            flash.pop({title: 'Information', body: constants['you_have_not_made_any_changes'],
                                type: 'info'});
                        });
                    } else {
                        //Flash the error message.
                        $('div#toast-container div.toast-info').fadeOut(300, function () {
                            flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'],
                                type: 'error'});
                        });
                    }

                    $(elem).hide();
                    $('div.library_comments textarea#commentEditBox_'+$(elem).attr('rel')).hide();
                    $('div.library_comments p#commentViewBox_'+$(elem).attr('rel')).show();
                    $('div.library_comments a#commentEditBtn_'+$(elem).attr('rel')).show();
                    $('div.library_comments p#commentViewBox_'+$(elem).attr('rel')).text(
                        $('div.library_comments textarea#commentEditBox_'+$(elem).attr('rel')).val())

                }, function errorCallback(response) {

                    //Flash message.
                    flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
                });
        }

}]);

//Add new forum
gillieNetFrontApp.controller('editForumController', ['$scope', 'ngDialog', 'CSRF_TOKEN', '$http',
    '$rootScope', 'flash', function($scope, ngDialog, CSRF_TOKEN, $http, $rootScope, flash) {

        //Show loader.
        $scope.loading = true;
        $scope.forum_details_to_be_edited = [];
        var forum_details_edited = {};

        //Async HTTP call to fill up all the data of forum.
        var data = {'forum_id': $('input#form_id').val()};
        $http({
            method: 'POST',
            url: PUBLIC_PATH + 'library/get-forum-details',
            data: data
        }).success(function (response) {
            //Hide loader.
            $scope.loading = false;
            $scope.forum_details_to_be_edited = response.forum_detail;
            $('div#forum_popup select[name=categories]').val($scope.forum_details_to_be_edited.category_id);
        }).error(function (response) {
            //Hide loader.
            $scope.loading = false;
        });

        //Saving data on button click.
        $scope.editForum = function() {
            $rootScope.loading                          = true;
            $scope.addForumDisableBtn                   = true;
            forum_details_edited.category        = $('div#forum_popup select[name=categories]').val();
            forum_details_edited.id              = $('div.library-detail input#form_id').val();
            forum_details_edited.title           = $scope.forum_details_to_be_edited.title;
            forum_details_edited.description     = $scope.forum_details_to_be_edited.description;

            $http({
                method: 'POST',
                url: PUBLIC_PATH + "library/save-forum",
                data: forum_details_edited,
                responseType: "JSON"
                })
                .then(function successCallback(response) {
                    // this callback will be called asynchronously
                    // when the response is available
                    
                    //Stop loading.
                    $rootScope.loading = false;
                    //Close the dialog box.
                    ngDialog.close();

                 /*   console.log(response['title']);
                    console.log(response.description);*/
                    $('#forum_title').text(response.data['title']);
                    $('#forum_title_cropped').text(response.data['title_cropped']);
                    $('#forum_description').text(response.data['description']);

                    //Flash the message.
                    flash.pop({title: 'Success', body: constants['forum_saved'], type: 'success'});

                }, function errorCallback(response) {

                    //Stop loading.
                    $rootScope.loading = false;
                    //Flash message.
                    flash.pop({title: 'Error', body: constants['please_fix_the_errors'], type: 'error'});
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                    $scope.errors = response.data;
                });
        }
}]);
