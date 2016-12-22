/**
 * Created by rkaur3 on 11/2/2016.
 */

/*Left menu section*/
//get details for left menu
gillieNetFrontApp.controller('leftpanelinfo',['$scope','ngDialog','CSRF_TOKEN','$http', function($scope,ngDialog,CSRF_TOKEN,$http) {

    angular.element(document).ready(function ()
    {   
       $scope.getUserFollowedInfo();
        $scope.getUserBasicInfo();



    });

    /**
     * Get followed user info.
     */
    $scope.getUserFollowedInfo = function(){
        $scope.loading=true;
        var data = {'userId': $('input[name=userId]').val()};
        $http.post(PUBLIC_PATH+'follower/getfollowed', data)
            .success(function(response) {
                $scope.loading=false;
                // console.log(response);
                if (response)
                {
                    $scope.follwedInfo = response;
                }
                if(response == 0) {
                    $scope.follwedInfo = [];
                    $scope.message = response;
                }
            })
    }

    $scope.getUserBasicInfo = function() {

        $('span.loading_about_me').show();
        var userId = $('input[name=userId]').val();
        /*The users basic info to populate left menu bar*/
        $http.get(PUBLIC_PATH + 'profile/basic-info/' + userId)
            .success(function (response) {
                $('span.loading_about_me').hide();
                if (response) {
                    $scope.userInfo = response;
                }
                else {
                    $scope.userInfo = [];
                }

            });
    }
}])


/**
 * Controller for notifications section on left profile menu
 */
gillieNetFrontApp.controller('notificationsController',['$scope', 'ngDialog', 'CSRF_TOKEN', '$http', '$timeout','flash', function($scope, ngDialog, CSRF_TOKEN, $http, $timeout, flash) {

    //==============================PUSHER  CODE=========================//

    //Added by hkaur5
    //instantiate a Pusher object with our Credential's key
    var pusher = new Pusher('68fdc4db98a39b3ee72c', {
        encrypted: true
    });

    //Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('my-channel');

    //Bind a function to a Event (the full Laravel class)
    channel.bind('App\\Events\\HelloPusherEvent', addMessage);

    function addMessage(data) {
        console.log(data.for_user_id );
        console.log($('#logged_in_userId').val());

        if(data.for_user_id == $('#logged_in_userId').val()){
            console.log(data.message);
            var html = '';
            html += '<li id="notif_'+data.message+'">'+data.message;
            html +='<span id="delete_notification" rel="'+data.id+'">';
            html +='<img style="cursor:pointer" src="'+PUBLIC_PATH+'/frontend/images/cross_icon_dark_olive.png">';
            html +='</span>';
            html +='</li>';

            //If more than one notifications are displaying in left panel then
            //last one.
            if($("ul.notifictaions_list li").length >1){
                $('ul.notifictaions_list li:last-child').remove();
            }
            $('#no_notifications_left_menu').remove(); // remove - no notifications available msg
            $('ul.notifictaions_list').prepend(html);

            //Browser notification using notification API
            generateBrowserNotification('Gillie Network', data.message)
        }

    }

    //===========================PUSHER CODE ENDS===========================//


    angular.element(document).ready(function () {

        $timeout(
            $('body').on('click','#delete_notification', function(){
                // console.log($(this).attr('rel'));
                $scope.deleteNotification($(this).attr('rel'), $(this).parent('li'), this);
            })
            , 5000
        );
        var userId = $('input[name=userId]').val();
        $scope.notifications_arr = [];
        

        /**
         * Get user's notifications
         * @author hkaur5
         *
         */
        $http({
            url: PUBLIC_PATH + 'get-notifications',
            method: "GET",
            params: {
                'offset': 0,
                'limit': 2,
                'for_user_id': $('input[name=userId]').val(),
            }
        })
            .success(function (response) {
                $('.loading_notifications').remove();//Hide loading
                if (response.notifications_data.notifications) {
                    var html = '';
                    for (i in response.notifications_data.notifications) {
                        html += '<li id="notif_' + response.notifications_data.notifications[i].id + '">';
                        html += response.notifications_data.notifications[i].text;
                        html += '<span id="delete_notification" rel = "' + response.notifications_data.notifications[i].id +'">';
                        html += '<img style="cursor:pointer" src="' + PUBLIC_PATH + '/frontend/images/cross_icon_dark_olive.png">';
                        html += '</span>';
                        html += '</li>';
                    }

                    $('ul.notifictaions_list').append(html); //Append html to show notifications.
                    /*angular.forEach(response.notifications_data.notifications, function(value, key) {
                     $scope.notifications_arr.push({'text':value.text})
                     });*/
                }
                else {
                    $scope.no_notifications_left_menu = true;
                }
            });
    });
        /**
         * Delete notification
         * @param notif_id
         * @param element (parent li tag)
         * @param cross_element (cross element which is clicked)
         */
        $scope.deleteNotification = function(notif_id, element, cross_element)
        {
            var notif_li = document.getElementById('notif_'+notif_id);
            $(notif_li).addClass('notification_fade_out'); //Fadeout notification element.
          //  $(notif_li).addClass('notification_fade_out');
            console.log($(notif_li));
            $(cross_element).hide();
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
                        element.remove();
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