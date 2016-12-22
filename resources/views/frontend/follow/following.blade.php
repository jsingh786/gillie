@inject('followwRepo', 'App\Repository\followwRepo')<!-- Inject class to use it's methods and properties -->
@inject('userProfileService', 'App\Service\Users\Profile')<!-- Inject class to use it's methods and properties -->
@extends('layouts.frontend.app')
@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/following.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/profile.css') }}">
@endsection

@section('content')
<div class="detail_box2"  ng-controller="FollowingController">
    <div class="about_info">
        <h1>Locals</h1>
        <p>
            <img src="{{ asset('frontend/images/black-drop.png') }}" alt="">
        </p>
    </div>
    <!--about_info-->
    <div class="local_search">
        <div class="l-scrh" id="searchdiv">
            <p><input type="text"  placeholder="Search" id="search">
                <img src="{{asset('frontend/images/search.png')}}" alt="">
            </p>
        </div><!-- l-scrh -->
        <div class="l-scrh2">
            <?php
            $he_she_or_you_are = 'He is ';
            if (Auth::Id() == $profileHolderObj->getId()) {
                $he_she_or_you_are = 'You are ';
            } else {
                switch ($profileHolderObj->getGender())
                {
                    case \App\Repository\usersRepo::GENDER_MALE:
                    case null:
                        $he_she_or_you_are = 'He is ';
                        break;
                    case \App\Repository\usersRepo::GENDER_FEMALE:
                        $he_she_or_you_are = 'She is ';
                }
            }
            ?>
                <span id="followingmsg" class="no_records_available_large_font" style="" ng-show="msg ==false "><?php echo $he_she_or_you_are.Config::get('constants.not_following_anybody'); ?></span>
                <ul>
                    <li ng-repeat="x in followingInfo" >
                        <span class="usr_img"><img src=[[x.path]] alt="">
                        </span> <a href="{{url('p')}}/[[x.id]]"><span class="usr_name"> [[x.name]]<br>[[x.place]]</span></a>

                        @if(Auth::Id() == $profileHolderObj->getId())
                            <button class="usr_btn btn btn-default" rel1="following_user_[[x.id]]" rel="[[x.fid]]"
                            id="following_[[x.id]]" ng-click="addAndRemoveFollower(x.fid, x.id)" followed="true">UNFOLLOW</button>
                        @else
                            [[x.followedRowId]]
                            <button class="usr_btn btn btn-default" rel1="following_user_[[x.id]]" ng-if="x.followingstatus == true" rel="[[x.fid]]"
                            id="following_[[x.id]]" ng-click="addAndRemoveFollower(x.followedRowId, x.id)" followed="false">UNFOLLOW</button>

                            <button class="usr_btn btn btn-default" ng-if="x.followingstatus == false" rel1="following_user_[[x.id]]" rel="[[x.fid]]"
                            id="following_[[x.id]]" ng-click="addAndRemoveFollower(x.followedRowId, x.id)" followed="true">FOLLOW</button>
                        @endif
                    </li>
                </ul>

                <span class="no_records_available_large_font" style="" id="message" ng-show="searchmsg == true ">No records found.</span>
            <span ng-show ="loading"  class="loading_data loading_gallery">
                <img src="{{asset('frontend/images/processing.svg')}}" />
        </span>
            <div class="view_more_btn" id="loadmore" ng-click="showMoreRecords()">
                    Load more
            </div>

        </div><!-- l-scrh2 -->


    </div><!--local_search-->
</div>
@endsection