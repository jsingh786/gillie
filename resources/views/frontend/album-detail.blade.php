@extends('layouts.frontend.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}" xmlns="http://www.w3.org/1999/html">
    <link rel="stylesheet" href="{{ asset('frontend/css/album-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload.css') }}" >
@endsection
@section('content')
    <div ng-controller="albumDetailController" class="detail_box2">
        <input id="album_id" type = "hidden" value="{{$album_id}}"/>
        <div class="about_info">
            <h1 class="ng-cloak" id="album_name" title= "Double click to edit album title" ng-hide="edit_album_name" ng-dblclick="edit_album_name = !edit_album_name" ng-click="edit_album_name = !edit_album_name">[[album_name]]</h1>

           <h1 class="ng-cloak" click-outside="saveAlbumName({{$album_id}})" ng-show="edit_album_name">
                <input maxlength="30" name="album_name" id= "edit_album_name" value="[[album_name]]">
           </h1>
            <p><img alt="" src="{{ asset('frontend/images/black-drop.png') }}"></p>
        </div>
        <!--about_info-->
        <div class="bg bg_gallery">
            <div class="create_album">
                @if($profileHolderObj->getId() == Auth::Id())
                    <button id="add_more_photos_btn" ng-click = "showAddMorePhotosPopup()" class="ca_btn">
                        <img src="{{asset('frontend/images/add.png')}}" alt="">
                        Add more photos
                    </button>
                @endif
                {{--<button class="ca_btn"><img src="{{asset('frontend/images/add.png')}}" alt=""> Video </button>--}}
            </div>

            <!--Photos listing-->
            <div class="gallery">
                <div ng-cloak
                     ng-repeat="photo in photos| orderBy:'-id'"
                     ng-mouseLeave = "delete_btn = !delete_btn"
                     ng-mouseEnter = "delete_btn = !delete_btn"
                     class="gallery_block"
                     id="photo_block_[[photo.id]]">
                    @if($profileHolderObj->getId() == Auth::Id())
                    <span ng-click="confirmPhotoDelete(photo.id,photo)"
                          rel1="[[photo]]" title="delete photo"
                          ng-show="delete_btn"
                          class="delete_btn_span" rel="[[photo.id]]"
                          id="delete_photo_[[photo.id]]">
                       <img  src="{{asset('frontend/images/cross_icon.png')}}">
                    @endif
                    </span>
                    <p>
<?php
                        $public_path = Config::get('constants.PUBLIC_PATH');
?>
                        <a href="javascript:;">
                            <img ng-click="showImagePopup([[photo.path_popup_thumbnail]]);" src={{$public_path}}./image.php?width=149&height=109&cropratio=2:1.4&image=[[photo.path_popup_thumbnail]] alt="">
                        </a>
                        {{--<span> </span>--}}
                    </p>
                </div>

            </div>
            <!-- Loading -->
            <span ng-cloak ng-show="loading_photos" id="loading_photos" class="loading_data loading_gallery">
                <img src="{{asset('frontend/images/processing.svg')}}" />
            </span>
            <!-- loading -->

            <!--photos listing End-->

            <div id="add_more_photos" class="gallery_popup glry_caret" style="display:none">
                <a ng-click="showAddMorePhotosPopup()" href="javascript:;" class="popup_close_btn">X</a>
               {{-- <h2>Album Title</h2>--}}
                <div id="add_photos" class="add_photos">
                   {{-- <input maxlength="255" type="text" id="album_name" >--}}
                {{--  <button class="ca_btn">ADD PHOTOS</button>--}}
                <!-- Blueimp html starts -->
                    <!-- The file upload form used as target for the file upload widget -->
                    <div class = "multi-photo-upload">
                        <form id="fileupload_add_more_photos" action="" method="POST" enctype="multipart/form-data">
                            <!-- Redirect browsers with JavaScript disabled to the origin page -->
                            <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                            <div class="fileupload-buttonbar">
                                <div class="fileupload-buttons">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="fileinput-button ca_btn">
                                <span>ADD PHOTOS</span>
                                <input type="file" name="files[]" multiple id = "add_photos" title="Add Photos">
                            </span>

                                    <!-- The global file processing state -->
                                    <span class="fileupload-process"></span>
                                </div>
                                <!-- The global progress state -->
                                <div class="fileupload-progress fade" style="display:none">
                                    <!-- The global progress bar -->
                                    <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    <!-- The extended global progress state -->
                                    <div class="progress-extended">&nbsp;</div>
                                </div>
                            </div>

                            <!-- The table listing the files available for upload/download -->
                            <div class="presentation-outer">
                                <table style="width:100%;" role="presentation" class="album_presentation">
                                    <tbody class="files"></tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <!-- blueimp html ends -->
                </div>


                <div class="upload">
                    <button id="post_photos" ng-click="postPhotos();" class="ca_btn">POST PHOTOS </button>
                </div>
                <!--upload-->

                <!-- The template to display files available for upload -->
                <script id="template-upload" type="text/x-tmpl">
                </script>
<?php
            $public_path = Config::get('constants.PUBLIC_PATH')
?>
                <!-- The template to display files available for download -->
                <script id="template-download" type="text/x-tmpl">
                {% for (var i=0, file; file=o.files[i]; i++) { %}
                        {% if (file.thumbnailUrl) { %}
                        <div class="template-download fade template_thumb">
                        {% } else { %}
                        <div class="template-download fade">
                        {% } %}
                        <span class="preview">
                            {% if (file.thumbnailUrl) { %}
                                <a href="javascript:;" title="{%=file.name%}"  data-gallery><img src="{%=file.imageResizeFilePathParams+file.thumbnailUrl%}"></a>
                            {% } %}
                        </span>

                        {% if (file.error) { %}
                            <div>
                                <span class="error">
                                    Error
                                </span>
                                <p>{%=file.error%}</p>

                                <button class="delete remove_thumb" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                               <img title = "remove photo" src="<?php echo $public_path?>/frontend/images/cross_icon.png" alt="X" title="Remove"  width="10px" height="10px" /></button>
                            </div>
                        {% } else { %}
                           <button class="delete remove_thumb thumbnail" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                           <img title = "remove photo" src="<?php echo $public_path?>/frontend/images/cross_icon.png" alt="X" title="Remove"  width="10px" height="10px" /></button>
                        {% } %}
                    </div>

                {% } %}
            </script>
            </div>
            <!--gallery_popup-->

        </div>
        <!-- bg -->
    </div>
    <script type="text/ng-template" id="templateId">

        <div id="target" ng-click="test()" ng-controller="tt">
            Click here
        </div>
    </script>
@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/profileController.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/albumDetailController.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/directives/clickoutsideDirective.js') }}" ></script>
    {{--Start Blueimp jQuery-File-Upload includes--}}
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/tmpl.min.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload-process.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload-validate.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload-image.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload-ui.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload-jquery-ui.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/main.js') }}" ></script>
    {{--End Blueimp jQuery-File-Upload includes--}}

@endsection
