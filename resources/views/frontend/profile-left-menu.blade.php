{{--==================================================================================================================--}}
{{--!Important: This partial view file could be used as profile part and needs profile holder ID as $profileHolderObj.--}}
{{--==================================================================================================================--}}
<div class="detail_box1" ng-controller="leftpanelinfo">
    <div class="about_user">
        <h5>
            <a href="{{url('about-me/'.$profileHolderObj->getId())}}">About Me</a>
            <span>
                <a href="{{url('p/'.$profileHolderObj->getId())}}"><img src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a>
            </span>
        </h5>
        <ul class="about_me_info_ul">
            <li ng-if="userInfo.name">
                Name: [[userInfo.name]]
            </li>

            <li ng-if="userInfo.address">
                Location: [[userInfo.address]]
            </li>

            <li ng-if="userInfo.profileobj && userInfo.dob">
                Date of Birth:
                [[userInfo.dob]]
            </li>

            <li ng-if="userInfo.gender == 1">Gender: Male</li>
            <li ng-if="userInfo.gender == 2">Gender: Female</li>
            <li ng-if="userInfo.marital_status === 1"> Marital status: Single</li>
            <li ng-if="userInfo.marital_status === 2"> Marital status: Married</li>

            <li ng-if="userInfo.email">
                Email:
                [[userInfo.email]]
            </li>

            <li ng-if="userInfo.profileobj && userInfo.phone">
                Phone: [[userInfo.phone]]
            </li>

            <li ng-if="userInfo.profileobj && userInfo.occupation">
                Occupation: [[userInfo.occupation]]
            </li>

            <li ng-if="userInfo.profileobj && userInfo.work">
                Work:
                [[userInfo.work]]
            </li>

            <!-- loading -->
               <span style="display:none" class="loading_data loading_about_me">
                    <img src="{{asset('frontend/images/processing.svg')}}"/>
                </span>
            <!-- loading -->

        </ul>
    </div><!--about_user-->
    <div class="about_user">
            <h5><a href="{{url('following/user-id/'.$profileHolderObj->getId())}}">Following</a>
            @if(Auth::Id() == $profileHolderObj->getId() )
                <span><a href=" {{url('following/user-id/'.$profileHolderObj->getId())}}"><img
                src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>
            @else
                <span><a href="javascript:;"><img src="{{ asset('frontend/images/edit_icon2.png') }}"
                alt=""></a> </span></h5>
            @endif
            <ul class="local_user">
                 <span ng-show="loading" class="loading_data loading_notifications">
                    <img src="{{asset('frontend/images/processing.svg')}}"/>
                </span>
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
                <span class="no_records_available_small_font "
                      ng-show="message != null"><?php echo $he_she_or_you_are.Config::get('constants.not_following_anybody'); ?></span>
                <li ng-cloak
                    ng-repeat="x in follwedInfo">
                    <span class="local_img"><img src="[[x.path]]" alt=""></span>
                    <a href="{{url('p') }}/[[x.id]]"><span class="local_text">[[x.name]] <br>[[x.place]]</span>
                    </a>
                </li>
            </ul>    <!--local_user-->
    </div><!--about_user-->

    @if(Auth::Id() == $profileHolderObj->getId() )

        <div class="about_user" ng-controller="notificationsController">
            <h5>
                <a href="{{url('notifications')}}">Notifications</a>
            <span>
                <a href="{{url('notifications')}}">
                    <img src="{{ asset('frontend/images/edit_icon2.png') }}" alt="">
                </a>
            </span>
            </h5>

            <ul class="notification notifictaions_list">
                <!-- loading -->
               <span class="loading_data loading_notifications">
                    <img src="{{asset('frontend/images/processing.svg')}}"/>
                </span>
                <!-- loading -->
                <!-- No notifications to display -->
                <span ng-cloak class="no_records_available_small_font" id="no_notifications_left_menu"
                      ng-show="no_notifications_left_menu">No notifications to display</span>
            </ul>    <!--Notifications-->


        </div><!--about_user-->

    @endif
    <div class="about_user">
        <h5><a href="javascript:;">Trophy Room</a><span> <a href="javascript:;"><img
                            src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>

        <ul class="Trophy_Room">
            <li><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
            <li><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
            <li><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
            <li class="pbtm"><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
            <li class="pbtm"><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
            <li class="pbtm"><a href="javascript:;"><img src="{{asset('frontend/images/user-4.jpg')}}" alt=""></a></li>
        </ul>    <!--Trophy_Room-->
    </div><!--about_user-->
</div><!--detail_box1-->

