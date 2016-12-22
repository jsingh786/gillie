@extends('layouts.backend.backend')
@section('pageTitle', 'View News')
@section('head')
    {{-- <link href="{{ asset('backend/css/dataTables.bootstrap.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('backend/css/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">View News</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Elements</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <div ng-controller="newsController" ng-cloak>
                                <form class="form-horizontal" name="viewNewsFrm" >
                                    <input type="hidden" ng-init="newsData.newsId='@if(isset($news_id)){{$news_id}}@endif'" ng-model="newsData.newsId" />
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <p class="form-control-static" ng-cloak>[[newsData.title]]</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword" class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <p class="form-control-static" ng-cloak>[[newsData.description]]</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword" class="col-sm-2 control-label" ng-cloak>Image</label>
                                        <div class="col-sm-10">
                                          <img src="{{  asset('backend/images/news/').'/' }}[[newsData.image]]" width="400" class="img-responsive">
                                        </div>
                                    </div>
                                    <a class="btn btn-default" href="{{url('admin/news')}}" type="reset">Back</a>
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
    <script  type="text/javascript" src="{{ asset('backend/js/angular/controllers/newsController.js') }}" ></script>
@endsection