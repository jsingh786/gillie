@extends('layouts.frontend.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/forum-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/write-forum.css') }}">
@endsection

@section('content')
<div class="inner-page-main"  ng-controller="libraryDetailController" >
    <div class="container">
        <div class="menu-tabs-main">
            <input type="hidden" id="forum_id" value="{{$forum_id}}"/>
            <div class="menu-tabs">
                <ul>
                    <li id="mtab1" class="selected"><a href="javascript:showTab(1,3)"><span class="b_img1"><img src="{{ asset('frontend/images/LIBRARY.png') }}" alt=""> </span><span class="b_img2"><img src="{{ asset('frontend/images/LIBRARY2.png') }}" alt=""> </span>LIBRARY</a></li>
                    <li id="mtab2"><a href="javascript:showTab(2,3)" ><span class="b_img1"><img src="{{ asset('frontend/images/Businesses.png') }}" alt=""> </span><span class="b_img2"><img src="{{ asset('frontend/images/Businesses2.png') }}" alt=""> </span>BUSINESSES</a></li>
                    <li id="mtab3"><a href="javascript:showTab(3,3)" ><span class="b_img1"><img src="{{ asset('frontend/images/local2.png') }}" alt=""> </span><span class="b_img2"><img src="{{ asset('frontend/images/local.png')}}" alt=""> </span>LOCALS</a></li>
                </ul>
            </div>
            <div class="menu-tabs-wrapper">
                <!-- Tab 1 Starts-->
                <div class="library-tab" id="tab1">
                    <div class="library-detail">
                        <input type="hidden" name="form_id" id = "form_id" value="{{ $forumDetailArr['id'] }}">
                        @if($forumDetailArr['isMyForum'])
                            <span class="topic_icons"> <a href="javascript:;" ng-click="editForumPopup()"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></a>
                            <a href="javascript:;" ng-click="confirmForumDelete({{ $forumDetailArr['id'] }})"><i aria-hidden="true" class="fa fa-trash-o"></i></a></span>
                        @endif

                        <div class="breadcrumb_list">
                            <ul class="forum_breadcrumb">
                                <li class="active"> <a href="{{ asset('library') }}">Library </a></li>
                                <li><a id="forum_title_cropped" href="javascript:;">{{ $forumDetailArr['title_cropped'] }}</a></li>
                            </ul>
                        </div>
                        <div class="library_detail_tab">
                            <h2 id="forum_title" class="forum_title">
                                {{ $forumDetailArr['title'] }}
                                <div style="margin-left:10px" star-rating rating="starRating"  click="click3(param, forum_detail.id)" mouse-hover="mouseHover3(param)" mouse-leave="mouseLeave3(param)"></div>
                                    <div ng-init="starRating = {{ $stars }}"></div>

                            </h2>
                           <p id="forum_description">
                              {{-- {{$forumDetailArr['description']}}--}}
                               {!!$forumDetailArr['description'] !!}
                           </p>
                        </div><!--library_detail_tab-->
                        <div class="library_detail_user">
                            <aside>
                                <figure>
                                    <img src="{{ $forumDetailArr['user_profile_photo'] }}" alt="">
                                </figure>
                                <span>
                                    <figcaption><b><a class ="forum_user_name" href="{{url('p/'.$forumDetailArr['user_id'])}}">{{ $forumDetailArr['user_name'] }}</a></b></figcaption>
                                    <figcaption>{{ $forumDetailArr['user_address'] }}</figcaption>
                                </span>
                            </aside>
                            <span class="date">Posted on {{ $forumDetailArr['date_posted'] }} </span>
                        </div><!--library_detail_user-->
                        <div class="library_comments"  ng-init="limit = 3">
                            <h4> Comments</h4>
                            <ul ng-repeat="fc in forum_comments.comments">
                                <li id = 'comment_[[ fc.id ]]'>
                                    <figure><img src="[[ fc.user_profile_photo ]]" alt=""></figure>

                                        <p class = "commentViewBox" id="commentViewBox_[[fc.id]]">[[ fc.message ]]</p>
                                        <span class="icons" ng-if="[[fc.isMyComment]] == 1">
                                            <a href="javascript:;" class='commentEditBtn' id="commentEditBtn_[[fc.id]]"
                                               onclick="showCommentEditControls(this)" rel="[[fc.id]]">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:;" class="commentSaveBtn" id="commentSaveBtn_[[fc.id]]"
                                               onclick="saveComment(this)" rel="[[fc.id]]" style = 'display:none;'>
                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:;" ng-click="removeComment(fc.id, fc)">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                        </span>
                                        <textarea class = 'commentEditBox' id="commentEditBox_[[fc.id]]" style = 'display:none;'>[[ fc.message ]]</textarea>
                                       <p class="time">Commented on by
                                           <a class ="forum_user_name" href="{{url('p')}}/[[fc.comment_by_user_id]]">[[ fc.comment_by_user ]]</a>
                                           (<time-ago from-time='[[fc.posted_date]]'>

                                           </time-ago>)
                                        </p>
                                     {{--   <a href="javascript:;" ng-click="showCommentEditBoxAndSaveBtn()" ng-show="showEditCommentBtn" ng-hide="">EDIT</a>--}}

                                </li>
                            </ul>

                            <a class="view_more" href="javascript:;" ng-hide="forum_comments.is_more_records === 0"
                                    ng-click="showMore(forum_detail.id)">View More</a>

                            <!-- Loading -->
                            <span ng-cloak ng-show="loading_comments" class="loading_data loading_comments">
                                <img src="{{asset('frontend/images/processing.svg')}}" />
                            </span>
                            <!-- loading -->
                            <p><textarea my-enter="addCmtClk(forum_detail.id)" cols="133" rows="5" ng-model="forum_comment.comment"> </textarea></p>

                        </div><!--library_comments-->
                    </div>
                </div>
            </div>
            <button class="gillie-btn add_comment" ng-disabled="checked" id="add_comment"ng-click="addCmtClk(forum_detail.id)">Add Comment</button>
        </div>
    </div>
</div>
@endsection
<!-- Tab 1 Ends-->
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/libraryDetailController.js') }}" ></script>

    <!-- Adding templates for popups etc. -->
    <script type="text/ng-template" id="editForumTemplate">
        @include('frontend.edit-forum')
    </script>
@endsection