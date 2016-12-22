/**
 * Created by hkaur5 on 12/16/2016.
 */
$(document).ready(function () {
    //Todo: make proper selector also change wallloader to wallLoader.
    $("#wallloader").show();

    //On clicking post button trigger postUpdateOnWall function
    // and check if no text is there then show dialog msg.
    $('button#submit_wallpost').click(function(){

        if( $("div.template-download ").length <= 0 &&
            $.trim($('textarea#wallpost_text').val() ) == ''
        )
        {
            showDialogMsg( 'Post update', constants['please_add_text_to_post_an_update'], 3000,
            {
                    buttons: [
                        {
                            text: "OK",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        },
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
        else{
            postUpdateOnWall($(this));
        }

    });
    //=====================================================================================

    jQuery.ajax({
        url: PUBLIC_PATH + 'user-wall-posts',
        type: "POST",
        data: {'userId': $('input[name=userId]').val(), 'limit':10, 'offset':0},
        dataType: "json",
        success: function (response) {
            var html = '';
            response.forEach(function(wallpost_data) {
                wallpostMaker(wallpost_data);
            });
            $("#wallloader").hide();
        }
    })

        //Trigger add photos file upload button on click of camera icon.
        $('#camera_icon').click(function(){
            if($('.video_wallpost_presentation div.template-download').length > 0){

                showDialogMsg( 'Confirm', constants['are_you_sure_you_want_to_discard_video_update_click_yes_if_you_want_to_continue'], 3000,
                    {
                        buttons: [
                            {
                                text: "Cancel",
                                click: function() {
                                    $( this ).dialog( "close" );
                                }
                            },
                            {
                                text: "Yes",

                                click: function() {
                                    $( this ).dialog( "close" );
                                    $('.video_wallpost_presentation div.template-download').remove();
                                    $('#add_photos_wallpost').trigger('click');
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
            else{
                $('#add_photos_wallpost').trigger('click');
            }

        });

        //Trigger add video file upload button on click of video camera icon.
        //Show dilaog if user is
        $('#video_camera_icon').click(function(){
            if($('.photos_wallpost_presentation div.template-download').length > 0){
                showDialogMsg( 'Confirm', constants['are_you_sure_you_want_to_discard_photos_update_click_yes_if_you_want_to_continue'], 3000,
                {
                    buttons: [
                        {
                            text: "Cancel",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        },
                        {
                            text: "Yes",
                            click: function() {
                                $( this ).dialog( "close" );
                                $('div.template-download').remove();
                                $('#add_videos_wallpost').trigger('click');
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
            // if video template exist i.e if
            else if($('.video_wallpost_presentation div.template-download').length > 0){
                showDialogMsg( 'Confirm', constants['you_can_add_one_video_at_a_time_are_you_sure_you_want_to_remove_previously_added_video'], 3000,
                {
                    buttons: [
                        {
                            text: "Cancel",
                            click: function() {
                                $( this ).dialog( "close" );
                            }
                        },
                        {
                            text: "Ok",

                            click: function() {
                                $( this ).dialog( "close" );
                                $('div.template-download').remove();
                                $('#add_videos_wallpost').trigger('click');
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
            else{
                $('#add_videos_wallpost').trigger('click');
            }

        });

    $('#user_comment').keydown(function (e){
        if(e.keyCode == 13){
            console.log($('#user_comment').val());
        }
    })

});

//Remove current users temp folder when page unloads.
$(window).on('beforeunload', function(){
    //Remove div where blueimp thumbnails are created on refreshing the page.
    $('div.template-download').remove();
    $.ajax(
        {
            url : PUBLIC_PATH+"remove-temp-folders-of-wallposts"
        });

});

/**
 * Make an ajax call to add a wallpost
 * and call wallpostMaker function
 * to display wallpost on newsfeed.
 * @author hkaur5
 *
 * @param elem
 */
function postUpdateOnWall(elem){

    elem.hide(); //Hide post button.
    $('span.posting_update').show();//Show loader

    if($('.photos_wallpost_presentation div.template-download').length > 0){
        jQuery.ajax({
            url: PUBLIC_PATH+ "post-photos-on-wall",
            type: "POST",
            dataType: "json",
            data: {
                'wallpost_text' :$('textarea#wallpost_text').val(),
                'wallpost_type' :2,
            },
            //cache: false,
            timeout: 50000,
            success: function( jsonData ) {
                return;
                elem.show();
                $('span.posting_update').hide();
                if(jsonData){
                    $('textarea#wallpost_text').val('');
                    wallpostMaker(jsonData);
                }
                else{


                }

            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });

    }
    else if($('.video_wallpost_presentation div.template-download').length > 0){
        jQuery.ajax({
            url: PUBLIC_PATH+ "post-videos-on-wall",
            type: "POST",
            dataType: "json",
            data: {
                'wallpost_text' :$('textarea#wallpost_text').val(),
                'wallpost_type' :3,
            },
            //cache: false,
            timeout: 50000,
            success: function( jsonData ) {
                return;
                elem.show();
                $('span.posting_update').hide();
                if(jsonData){
                    $('textarea#wallpost_text').val('');
                    wallpostMaker(jsonData);
                }
                else{


                }

            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });

    }
    else{
        jQuery.ajax({
            url: PUBLIC_PATH+ "post-text-on-wall",
            type: "POST",
            dataType: "json",
            data: {
                'wallpost_text' :$('textarea#wallpost_text').val(),
                'wallpost_type' :1,
            },
            //cache: false,
            timeout: 50000,
            success: function( jsonData ) {
                elem.show();
                $('span.posting_update').hide();
                if(jsonData){
                    $('textarea#wallpost_text').val('');
                    wallpostMaker(jsonData);
                }
                else{


                }

            },
            error: function(xhr, ajaxOptions, thrownError) {

            }
        });
    }

}



/**
 * Build html with
 * @param wallpost_data (array of wallpost data
 * {'wallpost_text':'abc','wallpost_posted_by_user_name':'harsimer kaur',
 * 'logged_in_user_photo':'path','wallpost_posted_by_user_photo':'path'})
 * @author hkaur5
 */
function wallpostMaker(wallpost_data){
    var html = ""
    html +='<div class="user_comment">';
        html +='<div class="l-scrh3">';
            html +='<span class="usr_img">';
                html +='<img src="'+wallpost_data["wallpost_posted_by_user_photo"]+'" alt="">';
            html +='</span>';
            html +='<p>'+wallpost_data["wallpost_text"]+'</p>';
            html +='<small>Posted By '+wallpost_data["wallpost_posted_by_user_name"];
            html +='Date: '+ new Date();
            html +='(<time class="timeago" datetime="'+wallpost_data["created_at"]+'">'+$.timeago('+wallpost_data["created_at"]+')+'</time>)';
            html +='</small>';
        html +='</div>';
        html +='<h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id pulvinar dolor. Pellentesque eget tempor orci. In in ante euismod, sollicitudin libero at, commodo mauris. Fusce ornare facilisis dictum.  </h5>';
        html +='<div class="kudo_tab">';
            html +='<p class="like_comment_count">';
            if(wallpost_data["likescount"]) {
                html += '<span id="countspan'+wallpost_data["wallpost_id"]+'"> ';
                    html += '<a href="javascript:;">';
                        html += '<img alt="" src="' + PUBLIC_PATH + 'frontend/images/like_icon_brown.png"> ';
                        html += wallpost_data["likescount"];
                    html += '</a>';
                html += '</span>';
            }
                html +='<span> ';
                    html += '<a href="javascript:;">';
                        html +='<img alt="" src="'+PUBLIC_PATH+'frontend/images/comment_icon._brown.png"> ';
                    html += '2</a>';
                html +='</span>';
            html += '</p>';
            html +='<p class="like_comment_btn">' ;

            if (wallpost_data["rowId"]) {
                html += '<button class="ca_btn" insertid="'+wallpost_data["rowId"]+'" like="1" id ="like'+wallpost_data["wallpost_id"]+'" onclick="likeOrUnlikewallPost('+wallpost_data["rowId"]+', '+wallpost_data["wallpost_id"]+')">';
                html += '<img alt="" src="http://localhost/gillie/public/frontend/images/like_icon.png"> ' ;
                html += ' Unlike ' ;
                html += '</button> ' ;
            }
            else{
                html += '<button class="ca_btn" insertid="" like="0" id ="like'+wallpost_data["wallpost_id"]+'" onclick="likeOrUnlikewallPost('+wallpost_data["wallpost_id"]+', '+wallpost_data["wallpost_id"]+')">';
                html += '<img alt="" src="http://localhost/gillie/public/frontend/images/like_icon.png"> ';
                html += ' Like ' ;
                html += '</button> ' ;
            }


                html += '<button class="ca_btn">' ;
                    html += '<img alt="" src="'+PUBLIC_PATH+'frontend/images/comment_icon.png">' ;
                    html += ' Comment ' ;
                html += '</button> ' ;
            html += '</p>';
        html +='</div>';//kudo_tab
    html +='<div class="comment_tab" id="commentdiv">';
    for(i in wallpost_data["commentinfo"])
    {
        html += '<div class="comment_sec"><div class="l-scrh3"><span class="usr_img"><img alt="" src="'+wallpost_data["commentinfo"][i]["logged_in_user_photo"]+'"></span>';
        html += '<p>'+wallpost_data["commentinfo"][i]["comment_text"]+'</p><small>Commented on by '+wallpost_data["commentinfo"][i]["comment_posted_by_user_name"]+'';
        html +='('+wallpost_data["commentinfo"][i]["comment_created_at"]+')</small></div></div>';
    }


        html +='<div class="comment_sec">';
            html +='<div class="l-scrh3">';
                html +='<span class="usr_img">' ;
                    html +='<img alt="" src="'+wallpost_data["logged_in_user_photo"]+'">';
                html +='</span>';
                html +='<p>';
                    html +='<input type="text" class="user-comment"  onkeydown="javascript: if(event.keyCode == 13) addComment(this)" postid="'+wallpost_data["wallpost_id"]+'" placeholder="Write A Comment">' ;
                html +='</p>';
                html +='<small>Press Enter to Post</small>';
            html +='</div>';//l-scrh3
        html +='</div>';//comment_sec
    html +='</div>';

    if($('#post_wallpost').length){

        $('form#post_wallpost').after(html);
    }else{

        $('div#newsfeed_container').after(html);
    }
}






function likeOrUnlikewallPost(id, buttonId) {
    $("#like" +buttonId).attr("disabled","true");
    if($("#like" +buttonId).attr('like') == "1")
    {
        jQuery.ajax({
            url: PUBLIC_PATH+'unlike-wall-post',
            type: "POST",
            data: {'rowId': $("#like" +buttonId).attr("insertId"),'wallpostId': buttonId},
            dataType: "json",
            success:function(response)
            {
                $('span#countspan' +buttonId).text(response["likescount"]);
                $('button#like' +buttonId).attr("insertId","");
                $('button#like' +buttonId).text('<img src="http://localhost/gillie/public/frontend/images/like_icon.png" alt="">Like');
                $('button#like' +buttonId).removeAttr("disabled");
                $('button#like' +buttonId).attr('like',"0");
            }
        })

    }
    else if($('button#like' +buttonId).attr('like') == "0")
    {
        jQuery.ajax({
            url: PUBLIC_PATH+'like-wall-post',
            type: "POST",
            data: {'wallpostId': id},
            dataType: "json",
            success: function (response) {
                if(response)
                {
                    var html = '';
                    html += '<img src="http://localhost/gillie/public/frontend/images/like_icon.png" alt="">'+response["likescount"]+'';
                    $('span#countspan' +buttonId).text(html);
                    $('button#like' +buttonId).text('Unlike');
                    $('button#like' +buttonId).attr("insertId", response["rowId"]);
                    $('button#like' +buttonId).removeAttr("disabled");
                    $('button#like' +buttonId).attr('like',"1");
                }
            }
        })
    }
}

function addComment(elem) {
    jQuery.ajax({
        url: PUBLIC_PATH+'post-comment-on-wall',
        type: "POST",
        data: {'wallpostId': $(elem).attr('postid'), 'text':$(elem).val()},
        dataType: "json",
        success: function (response) {
            if(response)
            {
                var html ='';
                html += '<div class="comment_sec"><div class="l-scrh3"><span class="usr_img"><img alt="" src="'+response["logged_in_user_photo"]+'"></span>';
                html += '<p>'+response["comment_text"]+'</p><small>Commented on by '+response["comment_posted_by_user_name"]+' ('+response["comment_created_at"]+')</small></div></div>';
            }
            $("#commentdiv").append(html);
        }
    })
}








