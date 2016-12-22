@extends('layouts.backend.backend')
@if(Request::path() == 'admin/banner/add-banner')
    @section('pageTitle', 'Add Banner')
@else
    @section('pageTitle', 'Edit Banner')  @endif
@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">@if(Request::path() == 'admin/banner/add-banner'){{ 'Add Banner' }} @else {{ 'Edit Banner' }} @endif</h2>
            </div>
        </div>
        <a href=" {{ url('admin/banner') }} " class="btn btn-primary" >Back</a><br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Elements</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <form name="addBannerForm" id="addBannerForm" ng-controller="bannerController" enctype="multipart/form-data">
                                <input type="hidden" ng-init="bannerData.bannerId='@if(isset($banner_id)){{$banner_id}}@endif'" ng-model="bannerData.bannerId" />
                                <div class="form-group">
                                    <label>Title<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="title" placeholder="Title" ng-model="bannerData.title">
                                </div>
                                <div class="form-group">
                                    <label>Description<span class="required">*</span></label>
                                    <textarea class="form-control" name=description" placeholder="Description" ng-model="bannerData.description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status<span class="required">*</span></label>
                                    <div class="radio" ng-init="bannerData.status=1">
                                        <label>
                                            <input type="radio" ng-model="bannerData.status"   value="{{ \App\Repository\bannerRepo::STATUS_ACTIVE }}"  >Active
                                        </label>
                                        <label>
                                            <input type="radio" ng-model="bannerData.status"  value="{{ \App\Repository\bannerRepo::STATUS_INACTIVE }}">In-Active
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Image<span class="required">@if(Request::path() == 'admin/banner/add-banner'){{ '*' }} @else {{ '' }} @endif</span></label>
                                    <img ng-if="bannerData.image"  src="{{ asset('backend/images/banners/').'/' }}[[bannerData.image]]" width="100" class="img-responsive img-thumbnail">
                                    <br/><br/>
                                    <input type="file" name="image" fileread="bannerData.file" />
                                </div>
                                <button type="button" class="btn btn-primary" ng-click="addBanner()">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script  type="text/javascript" src="{{ asset('backend/js/angular/controllers/bannerController.js') }}"></script>
@endsection