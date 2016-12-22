@extends('layouts.frontend.app')

@section('head')
    <link href="{{ asset('common/css/textAngular.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/profile.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/user-notes.css') }}" rel="stylesheet">

    <script src="{{ asset('common/js/textAngular/textAngular.min.js') }}"></script>
    <script src="{{ asset('common/js/textAngular/textAngular-rangy.min.js') }}"></script>
    <script src="{{ asset('common/js/textAngular/textAngular-sanitize.min.js') }}"></script>
@endsection

@section('content')
<div class="detail_box2" ng-controller="notesController">
    <span ng-cloak ng-show="loading" my-loader="60" id="spinloader"></span>
    <div class="about_info">
        <h1>Notes </h1>
        <p><img alt="" src="{{ asset('frontend/images/black-drop.png') }}"></p>
    </div>
    <!--about_info -->
    <!-- 15 sep add notes tooltip -->
    <div class="bg add_notes">
        <div class="create_album">
            <button class="ca_btn" ng-click="addNote()"><img src="{{ asset('frontend/images/add.png') }}" alt="">Add Notes</button>
        </div>
    </div><!--add_notes-->
    <div class="notes_bg" >
        <ul>
            <li ng-repeat="un in user_notes">
                <span ng-bind-html="un.notes | preserveHtml"></span>
                {{--[[un.notes|htmlToPlaintext]]--}}
            </li>
        </ul>
        {{--<a class="view_more" href ="javascript:;" ng-click="showMore()" ng-hide="user_notes.total_count === 0 || hide_view_more|| user_notes.is_more_records === 0">View More</a>--}}
        <div ng-cloack ng-click="showMore()" class="view_more_btn"
             ng-hide="user_notes.total_count === 0 || hide_view_more|| user_notes.is_more_records === 0">
            Load more
        </div>
        <div  class="no_records_available_large_font" ng-if="user_notes.length == 0">You have not saved any notes.</div>
    </div>
    <!-- bg -->
</div>
<!--detail_box2-->
@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('frontend/js/angular/controllers/notesController.js') }}" ></script>
@endsection
