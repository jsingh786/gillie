@extends('layouts.frontend.app')

@section('content')
    <div class="main-container height100">
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
        <div class="text-container">
            <div class="header">
                <div class="container">
                    <div class="header-main">
                        <div class="logo"><a href="javascript:;"><img src="{{ asset('frontend/images/logo.png') }}" alt=""></a></div>
                        <div class="nav-bar">
                            <a href="javascript:;" ng-click="slideToggle=! slideToggle"></a>
                            <div class="nav-outer slide-toggle" ng-show="slideToggle">
                                <div class="nav-menu" >
                                    <ul>
                                        <li><a href="javascript:;">Login</a></li>
                                        <li><a href="javascript:;">Signup</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="search-sec">
                    <div class="search-outer">
                        <input type="search" class="entr-txt" placeholder="Search">
                        <input type="submit" class="srch-btn" value="">
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
            </div>
        </div>

    </div>
@endsection
