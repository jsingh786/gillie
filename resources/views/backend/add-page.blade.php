@extends('layouts.backend.backend')
@if($pageId != "")
@section('pageTitle', 'Edit Page')
    @elseif($pageId == "")
        @section('pageTitle', 'Add Page')
 @endif
@section('head')
    <link href="{{ asset('backend/css/textangular-common.css') }}" rel="stylesheet">
@endsection
@section('content')

<div ng-controller="cmsController">
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

        <div class="row">
            <div class="col-lg-12">
                @if($pageId != "")
                    <h2 class="page-header data-table-hdr">Edit Page</h2>
                @elseif($pageId == "")
                <h2 class="page-header data-table-hdr">Add New Page</h2>
                    @endif
            </div>
        </div>
        <a href=" {{ url('admin/cms') }} " class="btn btn-primary" >Back</a><br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Elements</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <form name="addPageForm" id="addPageForm">
                                <input type="hidden" ng-init="pageId='<?php echo $pageId; ?>'" ng-model="pageId" />
                                <div class="form-group">
                                    <label>Title<span class="required">*</span></label>
                                    <input type="text" class="form-control" placeholder="title" ng-model="cmsData.title">
                                </div>
                                <div class="form-group">
                                    <label>Description<span class="required">*</span></label>
                                    <text-angular ta-paste="stripFormat($html)" placeholder="description" ng-model="cmsData.description"></text-angular>
                                </div>
                                <div class="form-group">
                                    <label>Status<span class="required">*</span></label>
                                    <div class="radio" ng-init="cmsData.status=1">
                                        <label>
                                            <input type="radio" ng-model="cmsData.status" value="{{ \App\Repository\CmsRepo::STATUS_ACTIVE }}" >Active
                                        </label>
                                        <label>
                                            <input type="radio" ng-model="cmsData.status"  value="{{ \App\Repository\CmsRepo::STATUS_INACTIVE }}">In-Active
                                        </label>
                                    </div>
                                </div>
                                <button type="button" title="Submit" class="btn btn-primary" ng-click="addNewCmsPage()">Submit</button>
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
    <script src="{{ asset('common/js/textAngular/textAngular.min.js') }}"></script>
    <script src="{{ asset('common/js/textAngular/textAngular-rangy.min.js') }}"></script>
    <script src="{{ asset('common/js/textAngular/textAngular-sanitize.min.js') }}"></script>
    <script src="{{ asset('backend/js/angular/controllers/cmsController.js') }}"></script>
@endsection