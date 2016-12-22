@extends('layouts.backend.backend')
@section('pageTitle', 'Manage Profile Rating')
@section('head')
    <link href="{{ asset('backend/css/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<div ng-controller="profileRatingController" ng-cloak>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">Manage Profile Rating</h2>
            </div>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3>Update Star Points</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <form name="editRatingForm" id="editRatingForm">
                        <div ng-repeat="rating in ratingData ">
                            <div class="form-group">
                                <label ng-bind-html = "showStars(rating.stars)" class="col-md-3 control-label" for="stars"></label>
                                <div class="col-md-9">
                                    <select ng-model="rating.points" ng-options="obj.value as obj.label for obj in pointValues"></select>
                                </div>
                            </div>
                            <br/>
                        </div>
                        <br/>
                        <button type="button" title="Submit" class="btn btn-primary" ng-click="editRatingPoints()">Submit</button>
                        <button type="button" title="Cancel" class="btn btn-default btn-md" ng-click="reRenderPoints()">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <script src="{{ asset('backend/js/angular/controllers/profileRatingController.js') }}"></script>
@endsection