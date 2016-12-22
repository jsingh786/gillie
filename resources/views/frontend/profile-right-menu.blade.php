<div class="detail_box3" >
    @if(Auth::Id() == $profileHolderObj->getId() )
        <div class="about_user">
            <h5><a href="javascript:;">Calendar</a><span> <a href="javascript:;"><img src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>

            <h4><img src="{{asset('frontend/images/celendar.png')}}" alt=""></h4>
        </div><!--about_user-->
        <div class="about_user">
            <h5><a href="javascript:;">Messages</a><span> <a href="javascript:;"><img src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>

            <ul class="bookmark">
                <li>  <h5>Mark <span>26 July,2016 </span> </h5>
                    Sed euismod, ligula vitae elementumSed euismod, ligula</li>
                <li>  <h5 >Mark <span>26 July,2016 </span> </h5>
                    Lorem ipsum dolor sit amet, consectetur</li>
            </ul>	<!--Notifications-->
        </div>
    @endif
        <div ng-cloak ng-controller="photosVideoMenuItemCtrl" class="photo_videos_right_menu about_user">
            <h5>
                <a href="{{url('gallery/user-id/'.$profileHolderObj->getId())}}">Photos / Videos</a>
                <span>
                    @if(Auth::Id() == $profileHolderObj->getId() )
                    <a href="{{url('gallery/user-id/'.Auth::Id())}}">
                        <img src="{{ asset('frontend/images/edit_icon2.png') }}" alt="">
                    </a>
                    @else
                        <a href="javascript:;">
                        <img src="{{ asset('frontend/images/edit_icon2.png') }}" alt="">
                    </a>
                    @endif
                </span>
            </h5>
            {{--<ul class="Trophy_Room" ng-show="albums_exists_right_menu">--}}
            <ul class="Trophy_Room photos_video">
<?php
                $public_path = Config::get('constants.PUBLIC_PATH');
?>

                <li ng-cloak
                    ng-repeat="album in albums"
                    class="">
                    <a href="{{$public_path}}/album/id/[[album.id]]/user-id/{{$profileHolderObj->getId()}}">
                        <img id="img_gallery" src="{{$public_path}}/image.php?width=67&height=70&cropratio=1.1:1.3&image=[[album.last_photo_path]]" alt="">
                    </a>
                </li>
                <!-- Loading -->
                <span ng-cloak ng-show="loading_photos" class="loading_data loading_gallery">
                    <img src="{{asset('frontend/images/processing.svg')}}" />
                </span>
                <!-- loading -->

                <!-- No albums to display -->
                <span  ng-cloak class="no_records_available_small_font" ng-show="no_albums_right_menu">No Albums to display</span>
            </ul>


        </div>

    @if(Auth::Id() == $profileHolderObj->getId() )
        <div class="about_user">
            <h5><a href="javascript:;">Bookmarks</a><span> <a href="javascript:;"><img src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>
            <ul class="notification">
                <li>Sed euismod, ligula vitae elementumSed euismod, ligula</li>
                <li>Lorem ipsum dolor sit amet, consectetur</li>
                <li>Sed euismod, ligula vitae elementumSed euismod, ligula</li>
                <li>Lorem ipsum dolor sit amet, consectetur</li>
                <li>Sed euismod, ligula vitae elementumSed euismod, ligula</li>
                <li>Lorem ipsum dolor sit amet, consectetur</li>
            </ul>	<!--Notifications-->
        </div>
            <!-- 15 sep -->
        <div class="about_user" ng-controller="mainNotesController">
            <h5><a href="{{ asset('notes') }}">Notes</a><span> <a href="{{ asset('notes') }}"><img src="{{ asset('frontend/images/edit_icon2.png') }}" alt=""></a> </span></h5>

            <ul class="notification user_notes">
                <li  ng-repeat="note in notes" id="notes_section_lines">
                    <span  class="notes_text" ng-bind-html="note.notes|htmlToPlaintext"></span>
                    {{-- <span class="notes_text" ng-bind-html="note.notes|preserveHtml"></span>--}}

                </li>
                {{--<li >
                    <span colspan="6" style="text-align:center">You have not saved any notes</span>
                </li>--}}
                <!-- No notes to display -->
                <span ng-if="notes.length == 0"  ng-cloak class="no_records_available_small_font" >You have not saved any notes</span>
            </ul>
            <!--Notifications-->
        </div><!-- 15 sep -->
    @endif<!--about_user-->
   <!--about_user-->
    <!-- Photos and videos END -->

</div><!--detail_box3-->
