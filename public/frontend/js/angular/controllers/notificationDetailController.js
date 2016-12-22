/**
 * Created by hkaur5 on 11/16/2016.
 */
gillieNetFrontApp.controller('notificationDetailController',['$scope','ngDialog','CSRF_TOKEN','$http','flash','$rootScope','$timeout', function($scope,ngDialog,CSRF_TOKEN,$http, flash, $rootScope, $timeout) {


    angular.element(document).ready(function () {
        $scope.no_notifications = false;
        $scope.notif_offset = 0;
        $scope.notif_limit = 20;
        $scope.notifications_detail_arr = [];
        $scope.loadNotifications(1);
        $scope.notif_load_more = false;
        $scope.notification_delete_cross = true;

        // On mouse enter in notification <li> show cross icon
        // and hide on mouse leave.
        $('ul').on('mouseenter','.notification_li',function () {

            var hide_cross = $(this).children('input.hide_cross').val(); // if cross icon is hidden for purpose then don't show it.

            if(hide_cross == 0)
            {
                $(this).children('span.notification_cross').css('display','inline-block');
            }
        });

        $('ul').on('mouseleave','.notification_li',function () {
            //console.log('enter');
            $(this).children('span.notification_cross').hide();
        });
        //========================================================

    });

    /**
     *  Load notifications and push notification
     *  data in notification_detail_arr which is used in
     *  ng-repeat to display user's notifications.
     * @author hkaur5
     * @param boolean first_time_load
     */
    $scope.loadNotifications = function(first_time_load)
    {

        $scope.loading_notification_list = true; // show Loading icon
        $('.view_more_btn').hide();// hide load more div

        $http({
            url: PUBLIC_PATH + 'get-notifications',
            method: "GET",
            params: {
                'offset': $scope.notif_offset,
                'limit': $scope.notif_limit,
                'for_user_id': $('input[name=userId]').val(),
            }
        })
            .success(function (response) {
                $scope.loading_notification_list = false;
                if(response.notifications_data)
                {
                    angular.forEach(response.notifications_data.notifications, function(value, key) {
                        $scope.notifications_detail_arr.push({'text':value.text,
                            'about_user_id':value.about_user_id,
                            'for_user_id':value.for_user_id,
                            'id':value.id}
                        )
                    });


                    //If more records exist.
                    console.log(response.notifications_data.is_more_records);
                    if(response.notifications_data.is_more_records)
                    {
                        $('.view_more_btn').show();
                        $scope.notif_offset  = parseInt($scope.notif_offset)+parseInt($scope.notif_limit);
                    }
                    else{
                        $('.view_more_btn').hide();
                    }
                }
                else
                {
                    if(first_time_load)
                    {
                        $scope.no_notifications = true

                    }
                  //  $scope.notif_load_more = false;

                }

            });
    }


    /**
     * Delete notification
     * @param notif_id
     */
    $scope.deleteNotification = function(notif_id, item, elem)
    {

        var cross = document.getElementById('notification_delete_'+notif_id);
        var notif_li = document.getElementById('notif_li_'+notif_id);

        $(notif_li).addClass('notification_fade_out'); // Add class to fadeout notification item.
        var loader  = $(cross).siblings('span#notification_delete_loader'); //Get sibling loader image span of cross.
        $(cross).hide(); //hide cross icon after clicking.
       // loader.show(); // show loading icon.
       // $(cross).siblings('.hide_cross').val(1);

        $http({
            url: PUBLIC_PATH+'delete-notifications',
            method: "GET",
            params: {
                'id': notif_id,
            }
        })
            .success(function (response) {
                if(response)
                {
                    var index = $scope.notifications_detail_arr.indexOf(item);
                    $scope.notifications_detail_arr.splice(index, 1);
                    flash.pop({title: 'Success', body: constants['notification_deleted'], type: 'success'})
                }
                else if(response == 2) {
                    flash.pop({title: 'Error', body: constants['notification_already_deleted'], type: 'error'});
                }
                else {
                    flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
                }
            }).error(function (response) {
            flash.pop({title: 'Error', body: constants['oops_some_error_occurred_please_try_again'], type: 'error'});
        });

    }

}]);