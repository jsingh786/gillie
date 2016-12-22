@extends('layouts.frontend.app')

@section('head')

    <link rel="stylesheet" href="{{ asset('frontend/css/welcome.css') }}">

    @endsection

@section('content')
        <div class="landing-main height100">
            <section class="slider height100">
                <div class="callbacks_container height100">
                    <ul class="rslides height100" id="slider">
                        <li class="height100">
                            <div class="hm_bnr height100">
                            </div>
                        </li>
                        <li class="height100">
                            <div class="hm_bnr2 height100">
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
        <div class="opesty-bg">
        </div>
        <div class="text-container" ng-controller="homeController">
            <div class="header">
                <div class="container">
                    <div class="header-main">
                      {{--  <input type="text" id="geocomplete">--}}
                        <div class="logo"><a href="javascript:;"><img src="{{ asset('frontend/images/logo.png') }}" alt=""></a></div>
                        <div class="nav-bar">
                           {{-- <a href="javascript:;" ng-click="slideToggle=! slideToggle"></a>--}}
                            <a class="popup-anchor" href="javascript:;" ng-click="toggle()" value="Toggle"></a>
                            <div class="nav-outer" >
                               {{-- <div class="nav-menu slide-toggle" ng-show="slideToggle">--}}
                                <div class="nav-menu" toggle style="display: none;">
                                    <ul>
                                        <li><a href="javascript:;" ng-click="openLoginPopup()">Login</a></li>
                                        <li><a href="javascript:;" ng-click="openSignUp()">Signup</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <form name="searchFrm">
                <div class="search-sec">
                    <div class="search-outer">
                        <input type="text" class="entr-txt" placeholder="Search" ng-model="searchText" required>

                        <input type="submit" ng-disabled="searchFrm.$invalid" class="srch-btn" value="" ng-click="searchBtnClk()">
                    </div>
                    <div class="banner-links">
                        <ul>
                            <li>
                                <a href="javascript:;" class="ques-link hvr-grow">Ask A Question</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="local-link hvr-grow">Find A Local</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="busins-link hvr-grow">Find A Business</a>
                            </li>
                        </ul>
                    </div>
                </div>
                </form>
            </div>
        </div>


@endsection
@section('footer')
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDS0gsUmaMUmdoiJSoFGKlOinKY6X3UegU&libraries=places"></script>
    <script  type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js"></script>
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/homeController.js') }}" ></script>


    <script type="text/javascript">
        $("#geocomplete_locc").geocomplete({

            details: "form#location_details"
        });


    </script>
    <!--call login dialog template-->
    <script type="text/ng-template" id="loginTemplate">
        @include('frontend.login')
   </script>

    <!-- call sign up dialog template -->
    <script type="text/ng-template" id="signupTemplate">
        @include('frontend.signup')
    </script>

    <!-- call forgot password dialog template -->
    <script type="text/ng-template" id="fwdTemplate">
        @include('frontend.forgot-pwd')
    </script>




@endsection
