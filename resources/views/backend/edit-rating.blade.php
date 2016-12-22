@extends('layouts.backend.backend')

@section('content')
@section('head')
    <link href="{{ asset('backend/css/textangular-common.css') }}" rel="stylesheet">
@endsection
<div ng-controller="profileRatingController">
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">Edit Rating Points</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Elements</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <form name="editRatingForm" id="editRatingForm">
                                <input type="hidden" ng-init="rateId='<?php echo $rateId; ?>'" ng-model="rateId" />
                                <div class="form-group">
                                    <label>Stars</label>
                                    <div ng-bind-html="ratingData.star_image"></div>
                                    {{--todo Remove commented code, if not required. --}}
                                    {{--<input type="text" class="form-control" placeholder="star" ng-model="ratingData.star">--}}
                                </div>


                                <div class="form-group">
                                    <label>Points</label>
                                    <input type="text" class="form-control"placeholder="points" ng-model="ratingData.points">
                                </div>
                                <button type="button" title="Submit" class="btn btn-primary" ng-click="editRatingPoints()">Submit</button>
                            </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection
@section('footer')
    <script src="{{ asset('backend/js/angular/controllers/profileRatingController.js') }}"></script>
@endsection