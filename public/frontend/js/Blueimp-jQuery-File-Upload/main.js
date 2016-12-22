/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */
$(function () {
    'use strict';
    var maxFiles = 10;
    var files_count = 0;

    // Initialize the jQuery File Upload widget for create album:
    $('#fileupload').fileupload({


        dropZone: $('form#fileupload'),
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: PUBLIC_PATH+'initialise-jquery-file-upload',
        dataType: 'json',
        sequentialUploads: true,
        previewCrop:true,
        maxFileSize: 1000000000, // 1 GB
        limitMultiFileUploadSize: 1000000000, // 1 GB
        limitMultiFileUploads: 50,
        //maxChunkSize: 1000000, //1 MB
        add: function (e, data) {

            //========================================FILE VALIDATIONS=========================================//
         
            //File count validation.
            files_count += parseInt(data.files.length);
            if(files_count > 50)
            {
                showDialogMsg('Oops!', "You cannot upload more than 50 files at once.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }

            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg,png,gif,JPG';
            if (allowdtypes.indexOf(fileType) < 0) {

                showDialogMsg('Oops!', "Files other than jpeg, jpg, png, gif format cannot be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }

            if(data.files[0].size >1024000000)
            {
                showDialogMsg('Oops!', "Files larger than allowed file size of 1GB cannot not be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            //========================================FILE VALIDATIONS=========================================//
            data.submit();

        }
    }).bind('fileuploadadd', function (e, data) {

        var fileCount = data.files.length;
        if (fileCount > maxFiles)
        {
            alert("The max number of files is "+maxFiles);
            return false;
        }

    });


    // Initialize the jQuery File Upload widget for adding photos to existing album:
    $('#fileupload_add_more_photos').fileupload({

        formData: {'album_id':$('#album_id').val()},
        dropZone: $('form#fileupload_add_more_photos'),
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: PUBLIC_PATH+'/initialise-jquery-file-upload-add-photos-to-album',
        dataType: 'json',
        sequentialUploads: true,
        previewCrop:true,
        maxFileSize: 1000000000, // 1 GB
        limitMultiFileUploadSize: 1000000000, // 1 GB
        limitMultiFileUploads: 50,
        add: function (e, data) {

            //========================================FILE VALIDATIONS=========================================//
            //File count validation.
            files_count += parseInt(data.files.length);
            if(files_count > 50)
            {
                showDialogMsg('Oops!', "You can upload 50 files at once.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;

            }
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg,png,gif,JPG';
            if (allowdtypes.indexOf(fileType) < 0) {

                showDialogMsg('Oops!', "Files other than jpeg,jpg,png,gif format cannot be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if(data.files[0].size >1024000000)
            {
                showDialogMsg('Oops!', "Files larger than allowed file size of 1GB cannot not be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            //========================================FILE VALIDATIONS=========================================//
            data.submit();

        }
    }).bind('fileuploadadd', function (e, data) {


        var fileCount = data.files.length;
        if (fileCount > maxFiles)
        {
            alert("The max number of files is "+maxFiles);
            return false;
        }

    });


     // Upload post on newsfeed - Post type photos.=============================================================
    // Initialize the jQuery File Upload widget for adding photos to existing album:
    var post_wallpost_selector_obj = $('form#post_photos_wallpost');
    post_wallpost_selector_obj.fileupload({

        dropZone: post_wallpost_selector_obj,
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: PUBLIC_PATH+'/add-photos-to-wallpost',
        dataType: 'json',
        sequentialUploads: true,
        previewCrop:true,
        maxFileSize: 1000000000, // 1 GB
        limitMultiFileUploadSize: 1000000000, // 1 GB
        limitMultiFileUploads: 50,
        add: function (e, data) {

            //========================================FILE VALIDATIONS=========================================//
            //File count validation.
            files_count += parseInt(data.files.length);
            if(files_count > 50)
            {
                showDialogMsg('Oops!', "You can upload 50 files at once.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;

            }
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg,png,gif,JPG';
            if (allowdtypes.indexOf(fileType) < 0) {

                showDialogMsg('Oops!', "Files other than jpeg,jpg,png,gif format cannot be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if(data.files[0].size >1024000000)
            {
                showDialogMsg('Oops!', "Files larger than allowed file size of 1GB cannot not be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            //========================================FILE VALIDATIONS=========================================//
            data.submit();

        }
    }).bind('fileuploadadd', function (e, data) {


        var fileCount = data.files.length;
        if (fileCount > maxFiles)
        {
            alert("The max number of files is "+maxFiles);
            return false;
        }

    });

    //Upload videos wallpost=================================================
    // Initialize the jQuery File Upload widget for adding videos:
    var videos_wallpost_form_obj =  $('#fileupload_videos_wallpost');
    videos_wallpost_form_obj.fileupload({


        dropZone: videos_wallpost_form_obj,
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: PUBLIC_PATH+'/add-videos-to-wallpost',
        dataType: 'json',
        sequentialUploads: true,
        previewCrop:true,
        maxFileSize: 1000000000, // 1 GB
        limitMultiFileUploadSize: 1000000000, // 1 GB
        limitMultiFileUploads: 1,
        add: function (e, data) {


            //========================================FILE VALIDATIONS=========================================//
            fileType = '';
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'mp4';

            if (allowdtypes.indexOf(fileType) < 0) {

                showDialogMsg('Oops!', "Files other than mp4 format cannot be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if(data.files[0].size >1024000000)
            {
                showDialogMsg('Oops!', "Files larger than allowed file size of 1GB cannot not be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if (data.files && data.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('img#target').attr('src', e.target.result);
                }
            }
            //========================================FILE VALIDATIONS=========================================//
            data.submit();

        }
    }).bind('fileuploadadd', function (e, data) {

        var fileCount = data.files.length;
        if (fileCount > maxFiles)
        {
            alert("The max number of files is "+maxFiles);
            return false;
        }

    });


    //Upload videos================================================================
    // Initialize the jQuery File Upload widget for adding videos:
    $('#fileupload_videos').fileupload({


        dropZone: $('form#fileupload_add_more_photos'),
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: PUBLIC_PATH+'/initialise-jquery-file-upload-for-videos',
        dataType: 'json',
        sequentialUploads: true,
        previewCrop:true,
        maxFileSize: 1000000000, // 1 GB
        limitMultiFileUploadSize: 1000000000, // 1 GB
        limitMultiFileUploads: 50,
        add: function (e, data) {


            //========================================FILE VALIDATIONS=========================================//
            //File count validation.
            files_count += parseInt(data.files.length);
            if(files_count > 50)
            {
                showDialogMsg('Oops!', "You can upload 50 files at once.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;

            }
            fileType = '';
            var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'mp4';
            if (allowdtypes.indexOf(fileType) < 0) {

                showDialogMsg('Oops!', "Files other than mp4 format cannot be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if(data.files[0].size >1024000000)
            {
                showDialogMsg('Oops!', "Files larger than allowed file size of 1GB cannot not be uploaded.", 3000,
                    {
                        buttons: [
                            {
                                text: "OK",
                                click: function(){
                                    $(this).dialog("close");
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
                return false;
            }
            if (data.files && data.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('img#target').attr('src', e.target.result);
                }
            }
            //========================================FILE VALIDATIONS=========================================//
            data.submit();

        }
    }).bind('fileuploadadd', function (e, data) {

        var fileCount = data.files.length;
        if (fileCount > maxFiles)
        {
            alert("The max number of files is "+maxFiles);
            return false;
        }

    });


    //This Part of code is common for all the controls using jquery file upload
    // Therefore multiple ids are given.
    if (window.location.hostname === 'blueimp.github.io')
    {
        // Demo settings:
        $('#fileupload,#fileupload_add_more_photos,#fileupload_videos').fileupload('option', {
            url: '//jquery-file-upload.appspot.com/',
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            maxFileSize: 1024000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
        });

        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                url: '//jquery-file-upload.appspot.com/',
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                        new Date())
                    .appendTo('#fileupload');
            });
        }
    }
    else
    {

        // Load existing files:
        var all_forms_selector_obj = $('#fileupload,#fileupload_add_more_photos,#fileupload_videos,#post_photos_wallpost');
        all_forms_selector_obj.addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: all_forms_selector_obj.fileupload('option', 'url'),
            dataType: 'json',
            context: all_forms_selector_obj[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
    }

});