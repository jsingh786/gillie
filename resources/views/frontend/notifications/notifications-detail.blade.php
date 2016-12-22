@extends('layouts.frontend.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/notifications-detail.css') }}">

    <link href="{{ asset('frontend/css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="detail_box2" ng-controller="notificationDetailController">

        <div class="about_info">
            <h1>Notifications </h1>
            <p><img alt="" src="{{ asset('frontend/images/black-drop.png') }}"></p>
        </div>
        <!--about_info -->
        <div class="notes_bg" >
            <ul>
<?php
        $public_path = Config::get('constants.PUBLIC_PATH')
?>
                <li class="notification_li" id="notif_li_[[notification.id]]" ng-repeat="notification in notifications_detail_arr">
                    <span class="notification_text">[[notification.text]]</span> <!-- Notification text-->
                    <input type="hidden" id="hide_cross_[[notiifcation.id]]" class="hide_cross" value="0">

                    <span class="notification_cross" id="notification_delete_[[notification.id]]" ng-show="notification_delete_cross"
                          ng-click = "deleteNotification(notification.id,notification)" rel="[[notification.id]]">
                        <img src="{{asset('frontend/images/cross_icon_dark_olive.png')}}">
                    </span><!-- Notification cross icon-->

                    <span class="notif_loader" id="notification_delete_loader"  rel="[[notification.id]]" style="display: none">
                        <img style="width:13px;height:12px" src="{{asset('frontend/images/processing.svg')}}">
                    </span><!-- Notification cross-loader icon.-->

                </li>
            </ul>


            <!-- No notifications exist Msg-->
            <div class="" ng-show="no_notifications">
                <span class="no_records_available_large_font">No Notifications to Display</span>
            </div>
            <!-- No notifications exist Msg End-->

            <!-- Loading -->
                <span ng-cloak ng-show="loading_notification_list" class="loading_data loading_gallery">
                    <img src="{{asset('frontend/images/processing.svg')}}" />
                </span>
            <!-- loading -->
            <!-- loading -->

            <div style="display:none" ng-click="loadNotifications(0)" class="view_more_btn">
                Load more
            </div>

        </div>
        <!-- bg -->
    </div>
    <!--detail_box2-->
@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/notificationDetailController.js') }}" ></script>
@endsection
