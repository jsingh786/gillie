@inject('followwRepo', 'App\Repository\followwRepo')<!-- Inject class to use it's methods and properties -->
@inject('userProfileService', 'App\Service\Users\Profile')<!-- Inject class to use it's methods and properties -->
{{--@include('frontend.demo')--}}

<style>
    a:hover{
    text-decoration : none;
    }

</style>
<div id="user-profile" ng-controller="profileHeaderController">
    <div class="container">
        <div class="client-detail">
            <div class="cd_left">
                <input type="hidden" id="profile_photo_hidden" value="[[userInfo_prof_header.profile_photo]]">
                <input type="hidden" id="def_prof_photo_hidden" value="[[userInfo_prof_header.default_profile_photo]]">

            <!-- Profile photo----->
                @if($profileHolderObj->getId() != Auth::Id())
               <figure>
                    <a href="javascript:;">
                        <img alt="" src="{{$userProfileService->getUserProfilePhoto($profileHolderObj->getId())}}">
                    </a>
                </figure>
                @else
                <div id="cropContainerEyecandy">
                    <img id="user_prof_photo" src={{$userProfileService->getUserProfilePhoto($profileHolderObj->getId())}}>
                </div>
                @endif
            <!-- Profile photo END----->

                <h3 ng-cloak >{{ $profileHolderObj->getFirstname()}} {{ $profileHolderObj->getLastname()}}
                    <span>{{$profileHolderObj->getAddress() }}</span>
                </h3>
            </div>
            <!--cd_left-->
            @if(Auth::Id() != $profileHolderObj->getId() )
            <div class="cd_left follow_message">
                @if($followwRepo->isUser1followedByUser2(Auth::Id(), $profileHolderObj->getId()) == true)
                <button follow="1" class="btn btn-default" id="follow" insertId="" ng-click="addAndRemoveFollower()">UNFOLLOW</button>
                @else
                    <button follow="0" class="btn btn-default" id="follow" insertId="" ng-click="addAndRemoveFollower()">FOLLOW</button>
                @endif
                <button class="btn btn-default" href="javascript:;">MESSAGE</button> </div>
            @endif
            <!--cd_left-->
            <input type="hidden" id="userId" name="userId" value="{{$profileHolderObj->getId() }}">
            <input type="hidden" id="logged_in_userId" name="logged_in_userId" value="{{Auth::Id() }}">
        </div>
        <!--client-detail-->


    </div>
    <!--container-->
</div>
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/croppic.js') }}" ></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/profile_header.js') }}" ></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/croppic.css') }}">


