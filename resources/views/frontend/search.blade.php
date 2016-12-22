@extends('layouts.frontend.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('frontend/css/library.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/write-forum.css') }}">
    <link rel="stylesheet" href="{{ asset('common/js/simplePagination/simplePagination.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/search.css') }}">
<style>

    tr.forum_listing_tr p.forum_user_address{ margin: 0 !important;}

</style>
@endsection


@section('content')
<div ng-app="libraryApp" class="inner-page-main"  ng-cloak>
    <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
    <div ng-controller="libraryController" class="container">
        <div class="menu-tabs-main">
            <div class="menu-tabs">
                <ul>
                    <li id="mtab1" class="selected">
                        <a href="javascript:" onclick="showTab(1,3)">
                            <span class="b_img1">
                                <img src="{{ asset('frontend/images/LIBRARY.png') }}" alt="">
                            </span>
                            <span class="b_img2">
                                <img src="{{ asset('frontend/images/LIBRARY2.png') }}" alt="">
                            </span>LIBRARY
                        </a>
                    </li>
                    <li id="mtab2">
                        <a href="javascript:" onclick="showTab(2,3)">
                            <span class="b_img1">
                                <img src="{{ asset('frontend/images/Businesses.png') }}" alt="">
                            </span>
                            <span class="b_img2">
                                <img src="{{ asset('frontend/images/Businesses2.png') }}" alt="">
                            </span>Businesses
                        </a>
                    </li>
                    <li id="mtab3">
                        <a href="javascript:" onclick="showTab(3,3)" >
                            <span class="b_img1">
                                <img src="{{ asset('frontend/images/local2.png') }}" alt="">
                            </span>
                            <span class="b_img2">
                                <img src="{{ asset('frontend/images/local.png') }}" alt="">
                            </span>Locals
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu-tabs-wrapper">
                <!-- Tab 1 Starts-->
                <div class="library-tab" id="tab1">
                    <div class="library-all">
                        <div class="library-filters">
                            <div class="select-category">
                                <select id="forum_categories"  name="filter_category"  ng-model="forumData.filter_category" ng-options="obj.txt for obj in forumCategoies track by obj.value" ng-change="getForumsAccCat()">
                                   {{-- <option value="1">General</option>--}}
                                </select>

                            </div>
                            <div class="filter-options">
                                <ul>
                                    <li><a href="javascript:;" ng-class ="{filterClass : activeMenu === 'latest'}" ng-click = "getLatestTrendingForums('latest')">Latest</a></li>
                                    <li><a href="javascript:;" ng-class ="{filterClass : activeMenu === 'trending'}" ng-click = "getLatestTrendingForums('trending')">Trending</a></li>
                                    <li><a href="javascript:;" ng-click="newForumPopup()">NEW TOPIC</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="library-grid">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <th width="370">Title</th>
                                    <th width="170">Date Posted</th>
                                    <th width="141">Activity</th>
                                    <th width="250">User</th>
                                    <th width="115">Views</th>
                                    <th width="150">Comments</th>
                                </tr>
                                {{--<tr dir-paginate="fl in forum_listing.forums| itemsPerPage:itemsPerPage"  current-page="pagination.current" >--}}
                                <tr dir-paginate="fl in forum_listing.forums| itemsPerPage:itemsPerPage"
                                    total-items="totalCount"
                                    current-page="pagination.current"
                                    class="forum_listing_tr">
                                    <td ng-click="forumDtlClk(fl.id)" style="cursor: pointer">[[fl.title|limitTo:35]]
                                        <div class="grid-star">
                                            <div star-rating rating="fl.rating_stars" read-only="true" max-rating="5" click="click2(param)" mouse-hover="mouseHover2(param)" mouse-leave="mouseLeave2(param)"></div>

                                        </div>
                                    </td>

                                    <td>[[fl.date_posted]]</td>
                                    <td ng-if="fl.activity_date">
                                        <time-ago from-time='[[fl.activity_date]]'></time-ago>
                                    </td>
                                    <td>
                                        <div class="user-grid-img">
                                            <img src="[[fl.profileImg]]" alt="">
                                        </div>
                                        <a href="{{url('p')}}/[[fl.forum_user_id]]" class="forum_user_name"><p>[[fl.user_name]]</p></a>
                                        <p style="margin:0 !important;" class="forum_user_address">[[fl.user_address]]</p>
                                    </td>
                                    <td>[[fl.no_of_views]]</td>
                                    <td>[[fl.forumCommentsCount]]</td>
                                </tr>
                                <tr ng-if="forum_listing.forums.length == 0">
                                    <td colspan="6" style="text-align:center">
<?php
                                        echo Config::get('constants.no_records_found')
?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{--pagination--}}
                    <dir-pagination-controls
                            max-size="3"
                            boundary-links="true"
                            template-url="{{ url('home/dir-pagination') }}"
                            on-page-change="pageChanged(newPageNumber)">
                    </dir-pagination-controls>


                </div>


                <!-- Tab 3 Starts-->
                <div class="local-tab" id="tab3" style="display:none;">
                    <div class="local-all">
                        <div class="local-left-list">
                            <ul id="locals_listing">
                                <li ng-repeat="x in localsInfo">
                                    <div class="local-user-sec">
                                        <a href="{{url('p')}}/[[x.id]]"><img src=[[x.image]]></a>
                                        <span class="local_tab"><p>[[x.name]]</p>
                                        <p>[[x.address]]</p></span>
                                    </div>
                                    {{--<div class="follow-btn">--}}
                                        {{--fsdfdsfdsf--}}
                                        {{--<button class="usr_btn btn btn-default" rel1="following_user_[[x.id]]" rel="[[x.fid]]"--}}
                                                {{--id="following_[[x.fid]]" ng-click="addAndRemoveFollower(x.fid, x.id)" followed="true">UNFOLLOW</button>--}}
                                    {{--</div>--}}
                                </li>
                            </ul>

                            <!-- Loading -->
                            <span style="" class="loading_data local_search_loading">
                                <img src="{{asset('frontend/images/processing.svg')}}" />
                            </span>
                            <!-- loading -->

                            <span style="display:none" class="local_listing_no_records no_records_available_large_font">
                            <?php
                                echo Config::get('constants.no_records_found')
                            ?>
                            </span>

                            <span style="display:none" class="local_listing_no_search no_records_available_large_font">
                            <?php
                                echo Config::get('constants.you_may_use_filters_to_search_locals')
                            ?>
                            </span>

                        </div>

                        <div class="local-right-form">
                            <h3>Refine Your Result</h3>
                            <div class="form-inner">
                                <div class="select-state">
                                    <select id="states">
                                        <option value="0">select</option>
                                        <option ng-repeat="state in states" value="[[state.id]]">[[state.name]]</option>
                                    </select>
                                </div>
                                <div class="select-state">
                                    <select id="cities">
                                        <option value="0">select</option>
                                        <option ng-repeat="city in cities" value="[[city.id]]">[[city.name]]</option>
                                    </select>
                                </div>
                                <div class="form-checks">
                                    <p>Activity</p>
                                    <div class="check-list">
                                        <ul>
                                          @foreach ($activities as $activity)
                                            <li class="activities_list">
                                                <label>
                                                    <input value="{{$activity['id']}}" type="checkbox">
                                                    {{$activity['name']}}
                                                </label>
                                            </li>
                                          @endforeach
                                            {{--<li><label><input type="checkbox">Salt water fishing</label></li>
                                            <li><label><input type="checkbox">Fresh water fishing</label></li>--}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-checks">
                                    <p>Weapon</p>
                                    <div class="check-list">
                                        <ul>
                                            @foreach ($weapons as $item)
                                            <li class="weapons_list"><label><input value="{{$item['id']}}" type="checkbox">{{$item['name']}}</label></li>
                                          @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-checks">
                                    <p>Species</p>
                                    <div class="check-list">
                                        <ul>
                                            @foreach ($species as $item)
                                            <li class="species_list"><label><input value="{{$item['id']}}" type="checkbox">{{$item['name']}}</label></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-checks">
                                    <p>Property</p>
                                    <div class="check-list">
                                        <ul>
                                            @foreach ($properties as $item)
                                                <li class="properties_list"><label><input value="{{$item['id']}}" type="checkbox">{{$item['name']}}</label></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-btns">
                                    <input type="button" onclick = "searchLocals(0, 1)" value="Search">
                                   {{-- <input type="button" value="Advanced Criteria">--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="local_search_pagination">

                    </div>
                </div>


            </div>
        </div>


    </div>
</div>
    <div id="locals_pagination">

    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('common/js/simplePagination/jquery.simplePagination.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/angular/controllers/libraryController.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('frontend/js/newsFeed.js') }}" ></script>
    <!-- Adding templates for popups etc. -->
    <script type="text/ng-template" id="newForumTemplate">
        @include('frontend.new-forum')
    </script>

    <script type="text/ng-template" id="thankYouTemplate">
        @include('frontend.common.thankyou')
    </script>

    <script type="text/ng-template" id="loginTemplate">
        @include('frontend.login')
    </script>

    <script>

    </script>
@endsection
