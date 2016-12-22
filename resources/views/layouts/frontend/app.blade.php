<!doctype html>
<html>
    <head>
        @include('frontend.common.header-scripts')
        @yield('head')
    </head>
    <body class="gillie_body" id="gillie_body" ng-app="gillieNetworkFrontApp" ng-cloak>
       {{-- <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>--}}
@if(Request::path() != "/")
        <div class="main-container">
            @include('frontend.common.header')


@if($enable_profile_menu)
@if($enable_profile_menu == true)
       <div >
           @include('frontend.profile-header')
           <div id="user_detail_info">
               <div class="container">
                   <div class="row">
                       @include('frontend.profile-left-menu')
@endif
                       @endif
@else
   <div class="main-container height100">
@endif

@yield('content')

@if($enable_profile_menu == true)
                       @include('frontend.profile-right-menu')
                    </div><!--row-->
    </div>
                </div><!--container-->
            </div><!--user_detail_info-->
        </div> <!-- controller div-->
@endif
   </div>
   @include('frontend.common.footer')
   @include('frontend.common.footer-scripts')
   @yield('footer')
</body>
</html>