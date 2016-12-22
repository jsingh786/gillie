
/**
 * Created by hkaur5 on 11/15/2016.
 */
//==============================PUSHER  CODE=========================//
/*
//instantiate a Pusher object with our Credential's key
var pusher = new Pusher('68fdc4db98a39b3ee72c', {
    encrypted: true
});

//Subscribe to the channel we specified in our Laravel Event
var channel = pusher.subscribe('my-channel');

//Bind a function to a Event (the full Laravel class)
channel.bind('App\\Events\\HelloPusherEvent', addMessage);

function addMessage(data) {
    console.log(data);
    var html = '';
    html += '<li id="'+data.message+'">'+data.message;
    html +='<span onclick="deleteNotification('+data.id+')">';
    html +='<img style="cursor:pointer" src="'+PUBLIC_PATH+'/frontend/images/cross_icon_dark_olive.png">';
    html +='</span>';
    html +='</li>';

    $('ul.notifictaions_list li:last-child').remove();
    $('#no_notifications_left_menu').remove(); // remove - no notifications available msg
    $('ul.notifictaions_list').prepend(html);

    //Browser notification using notification API
    var notification = new Notification('New Notification', {
        body: data.message
    });
    notification.onshow = function() {
        console.log('Notification shown');
    };
    //alert(data.message);
}

//===========================PUSHER CODE===========================//
$(document).ready(function() {
    var userId = $('input[name=userId]').val();
    /!**
     * Get user's notifications
     * @author hkaur5
     *
     *!/

    /!**
     * Get user's notifications
     * @author hkaur5
     *
     *!/
    jQuery.ajax({
        url: "/" + PROJECT_NAME + "get-notifications",
        type: "GET",
        dataType: "json",
        data: {
            'offset': 0,
            'limit': 2,
            'for_user_id': $('input[name=userId]').val(),
        },
        timeout: 50000,
        success: function (jsonData) {
            $('.loading_notifications').hide();
            if (response.notifications_data.notifications) {


                var html = '';
                for (i in response.notifications_data.notifications) {

                    html += '<li id="' + response.notifications_data.notifications[i].id + '">';
                    html += response.notifications_data.notifications[i].text;
                    html += '<span onclick="deleteNotification(' + response.notifications_data.notifications[i].id + ',this )">';
                    html += '<img style="cursor:pointer" src="' + PUBLIC_PATH + '/frontend/images/cross_icon_dark_olive.png">';
                    html += '</span>';
                    html += '</li>';
                }

                $('ul.notifictaions_list').append(html);

                /!*angular.forEach(response.notifications_data.notifications, function(value, key) {
                 $scope.notifications_arr.push({'text':value.text})
                 });*!/
            }
            else {
                $('.no_notifications_left_menu').show();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

        }
    });
});
/!**
 * Delete notification
 * @param notif_id
 *!/
function deleteNotification(notif_id, element)
{
    alert('ss');
    /!*      $http({
     url: PUBLIC_PATH+'delete-notifications',
     method: "GET",
     params: {
     'id': notif_id,
     }
     })
     .success(function (response) {

     });*!/

}

*/
