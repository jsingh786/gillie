
/**
 * Created by hkaur5 on 10/28/2016.
 */

$(document).ready(function () {
/*   var notification = new Notification('Email received', {
   body: 'You have a total of 3 unread emails'
  });
  notification.onshow = function() {
  console.log('Notification shown');
 };*/

    $('div#cropContainerEyecandy').mouseover(function(){
        console.log('mouse');
        $('div.cropControls.cropControlsUpload').css(' background-color','rgba(0, 0, 0, 0.35)');
    });
});

//=======================================================================================================//
//========================================Cropppic Plugin================================================//
//=======================================================================================================//
/**
 * Below code is for croppic plugin with
 * all callback functions.
 * Croppic options include ajax call
 * to upload image and upload cropped image.
 * @author hkaur5
 *
 * @type {*|jQuery|HTMLElement}
 */

//Define object for container where croppic is applied.
//cropContainerEyecandy is id of div where croppic is applied.
var eyeCandy = $('#cropContainerEyecandy');
var croppedOptions = {
rotateControls:false,
uploadUrl: PUBLIC_PATH+'upload-profile-photo',
cropUrl: PUBLIC_PATH+'save-cropped-profile-photo',
loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
cropData:{
    'width' : eyeCandy.width(),
    'height': eyeCandy.height(),
    'token':  $('[name=_token]').attr('content')
},
onAfterImgCrop: function(){
    var profile_photo_path = $('.croppedImg').attr('src');
    console.log($('.croppedImg').attr('src'));
    $('#profile_photo_hidden').val(profile_photo_path);
    $('#profile_photo_exist').val('1');
    console.log('onAfterImgCrop');

    $('.cropControls.cropControlsUpload').children('i.cropControlRemoveCroppedImage').remove();
},
onBeforeImgUpload: function(){
    $('#user_prof_photo').remove();
    console.log('onBeforeImgUpload');
},
onReset:function()
{
    var profile_photo = $('#profile_photo_hidden').val();
    $('.cropControlsUpload').before('<img class="croppedImg" id="user_prof_photo" src="'+profile_photo+'">');
    console.log('reset');
},
onImgDrag:		function(){ console.log('onImgDrag');},
onImgZoom:		function(){ console.log('onImgZoom') },
onBeforeImgCrop: 	function(){ console.log('onBeforeImgCrop') },


onError: function(errormsg){ console.log('onError:'+errormsg) }
};

//Initiating croppic options.
var cropperBox = new Croppic('cropContainerEyecandy', croppedOptions);
//=======================================================================================================//
//========================================Cropppic Plugin END============================================//
//=======================================================================================================//

