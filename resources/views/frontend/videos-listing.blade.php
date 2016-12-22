@extends('layouts.frontend.app')
@section('head')

    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/video-listing.css') }}">

@endsection
@section('content')
    <div ng-controller="videosListingCtrl" class="detail_box2">
        <input type="hidden" id="current_video_path" value="">
        <div class="about_info">
            <h1>Videos</h1>
            <p><img alt="" src="{{ asset('frontend/images/black-drop.png') }}"></p>
        </div>
        <!--about_info-->
        <div class="bg bg_gallery">
            <!--create_album-->
            <div class="gallery">
                <div ng-cloak
                     ng-repeat="video in videos| orderBy:'-id'"
                     ng-mouseLeave = "delete_btn = !delete_btn"
                     ng-mouseEnter = "delete_btn = !delete_btn"
                     class="gallery_block"
                     video_id="[[video.id]]"
                    id="video_block_[[video.id]]">
                    <img ng-click="showVideoPlayerPopup([[video.path]])" class="play_icon" src="{{asset('frontend/images/play_icon.png')}}"/>

                    @if($profileHolderObj->getId() == Auth::Id())
                    <span title="delete video"
                          ng-show="delete_btn"
                          class="delete_btn_span"
                          rel="[[video.id]]"
                          id="delete_video_[[video.id]]">
                        <img  src="{{asset('frontend/images/cross_icon.png')}}">
                    </span>
                    @endif
                    <p ng-click="showVideoPlayerPopup([[video.path]])">
<?php
                        $public_path = Config::get('constants.PUBLIC_PATH');
?>
                        <a class="gallery_video_anchor" href="javascript:;">
                            <img  src={{$public_path}}./image.php?width=149&height=109&cropratio=2:1.4&image=[[video.thumb_path]] alt="">
                        </a>

                        {{--<span> </span>--}}
                    </p>
                </div>

                <!-- No Album Exist Msg-->
                <div ng-cloak class="no_videos_msg no_records_container" ng-show="no_videos">
                    <span>No Videos to Display</span>
                </div>

            </div>
            <!-- Loading -->
            <span ng-show="loading_photos" class="loading_data loading_gallery">
                    <img src="{{asset('frontend/images/processing.svg')}}" />
            </span>
            <!-- loading -->
        </div>
        <!-- bg -->
    </div>
    <!--<div id="video_player_popup_html">
        <div id="video_popup">Loading the player...</div>
    </div>-->
@endsection
@section('footer')

    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/videoListingController.js') }}" ></script>
    <script src="https://content.jwplatform.com/libraries/qAkRysIB.js"></script>
@endsection
