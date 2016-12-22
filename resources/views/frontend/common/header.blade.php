@inject('UserProfileServer', 'App\Service\Users\Profile')
<div class="inner-page-header">
    <div class="gillie_container">
        <div class="header-main" ng-controller="headerController">
            <div class="logo">
                <a href="{{asset('library')}}">
                    <img src="{{ asset('frontend/images/logo2.png') }}" alt="" class="inner-page-logo"></a>
            </div><!-- logo -->
            <div class="header-search">
                <input type="hidden" id="local_tab_active" value="0" >
                <input type="hidden" id="offset_local_search" value="0" >
                <input type="hidden" id="limit_local_search" value="3" >

                <div class="search-outer-inner">
                    <form name="upperSrchFrm">
                        <input type="text" placeholder="Search" class="entr-txt" ng-model="upper_search" id="upper_search">
                        <input type="submit" value="" class="srch-btn" ng-click="upperSearchClk()" id="upper_search_btn">
                    </form>
                </div><!-- search-outer-inner -->
                <p><a href="javascript:;" ng-click="clearSearchClk()">Clear Search</a></p>
            </div><!-- header-search -->
            @if(Auth::check())
            <div class="nav-bar">
                <input type="hidden" id="loggedUserId" value="{{Auth::user()->getId()}}">
               {{-- <a href="javascript:;" ng-click="slideToggle=! slideToggle"></a>--}}
                <div id="sideClickNav">
                <a class="popup-anchor"  href="javascript:;" ng-click="toggleHeader()"></a>
                </div>
                <div class="user-detail">
                    <figure>
                        <img src="{{ $UserProfileServer->getUserProfilePhoto(Auth::Id()) }}" alt="">
                    </figure>
                    <a href="{{url('p/'.Auth::Id())}}">
                        <p class="user-detail-p">{{ Auth::user()->getFirstname() }}</p>
                    </a>
                        <p class="orange user-detail-p">{{ Auth::user()->getAddress() }}</p>

                </div>
                <div class="nav-outer" >
                   {{-- <div class="nav-menu slide-toggle"  ng-show="slideToggle">--}}
                    <div class="nav-menu"  toggle style="display: none;">
                        <ul>
                            <li><a class="nav-bar-anchr" href="{{url('p/'.Auth::Id())}}">My Dashboard</a></li>
                            <li><a class="nav-bar-anchr" href="javascript:;">Settings</a></li>
                            <li><a class="nav-bar-anchr" href="{{ url('auth/logout') }}">Logout</a></li>
                        </ul>
                    </div><!--nav-menu-->
                </div> <!--nav-outer-->
            </div><!-- nav-bar -->
                @endif
        </div><!-- header-main ptop30 -->

    </div>
</div><!-- inner-page-header -->
