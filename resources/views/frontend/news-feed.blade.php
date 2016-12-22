@extends('layouts.frontend.app')
@section('head')
@inject('userProfileService', 'App\Service\Users\Profile')<!-- Inject class to use it's methods and properties -->

    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/newsFeed.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/js/Blueimp-jQuery-File-Upload/jquery.fileupload.css') }}" >

@endsection
@section('content')
    <div class="detail_box2">
        <div class="about_info">
            <h1>Newsfeed </h1>
            <p><img alt="" src="{{asset('frontend/images/black-drop.png')}}"></p>
        </div>
        <!--about_info-->

        <div class="local_search" id="newsfeed_container">
            <input  id="wallpost_type" type="hidden" value="1">
            @if($profileHolderObj->getId() == Auth::Id())
                <div class="l-scrh3">
                    <span class="usr_img"><img alt="" src="{{$userProfileService->getUserProfilePhoto($profileHolderObj->getId())}}"> </span>
                    <textarea id="wallpost_text" type="text" placeholder="What’s on your mind?" max-length=""></textarea>
                </div>
                <!-- l-scrh3 -->
                <div class="post-1">
                    <span>
                        <a id="camera_icon" href="javascript:;"><img src="{{asset('frontend/images/camera_icon.png')}}" alt=""></a>
                        <a id="video_camera_icon" href="javascript:;"> <img src="{{asset('frontend/images/video_icon.png')}}" alt=""></a>
                    </span>
                    <button id="submit_wallpost" class="ca_btn" type="submit">Post</button>
                    <!-- Loading -->
                    <span style="display:none;" class="loading_data loader_small_size posting_update">
                        <img src="{{asset('frontend/images/processing.svg')}}" />
                    </span>
                    <!-- loading -->
                </div>
                @endif

                <form id="post_photos_wallpost" action="" method="POST" enctype="multipart/form-data">
                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                    <noscript>
                        <input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="fileupload-buttonbar">
                        <div class="fileupload-buttons">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="fileinput-button ca_btn">
                                <span>ADD VIDEOS</span>
                                <input style="display:none" class="ca_btn" type="file" name="files[]" multiple id = "add_photos_wallpost" title="Add videos">
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
                        <table style="width:100%;" class="photos_wallpost_presentation" role="presentation" rel="presentation_photos_wallpost">
                            <tbody class="files"></tbody>
                        </table>
                    </div>
                </form>

                <span class="loading_data loading_notifications" id="wallloader"><img src="http://localhost/gillie/public/frontend/images/processing.svg"></span>
                <form id="fileupload_videos_wallpost" action="" method="POST" enctype="multipart/form-data">
                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                    <noscript>
                        <input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="fileupload-buttonbar">
                        <div class="fileupload-buttons">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="fileinput-button ca_btn">
                                <span>ADD VIDEOS</span>
                                <input class="ca_btn" type="file" name="files[]" id = "add_videos_wallpost" title="Add videos">
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
                        <table style="width:100%;" class="video_wallpost_presentation" role="presentation"
                               rel="presentation_videos_wallpost">
                            <tbody class="files">
                            </tbody>
                        </table>
                    </div>

                </form>


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
                                <a href="javascript:;" title="{%=file.name%}" data-gallery><img src="{%=file.imageResizeFilePathParams_small_thumb+file.thumbnailUrl%}"></a>
                            {% } else if (file.display_name){ %}

	 				            <label title="{%=file.display_name%}" class="attachment-lalbel">{%=file.display_name_trimmed%}</label>
					        {% } %}
                        </span>
                        {% if (file.error) { %}
                            <div style="width:160px">
                                <span class="error">
                                    Error
                                </span>
                                <p>
                                    {%=file.error%}
                                </p>
                               <button class="delete remove_thumb" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                    <img title = "remove video" src="<?php echo $public_path?>/frontend/images/cross_icon.png" alt="X" title="Remove"  width="10px" height="10px" />
                               </button>
                            </div>
                        {% } else { %}
                            <button class="delete remove_thumb thumbnail" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                <img title = "remove photo" src="<?php echo $public_path?>/frontend/images/cross_icon.png" alt="X" title="Remove"  width="10px" height="10px" />
                            </button>
                        {% } %}
                    </div>

                {% } %}
            </script>
            {{--@endif--}}
            <!-- post-1 -->
        </div>
        <!-- local_search -->

    </div>
@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/timeAgo.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/newsFeed.js') }}" ></script>

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
