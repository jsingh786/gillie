@extends('layouts.backend.backend')
@if(Request::path() == 'admin/news/add-news') @section('pageTitle', 'Add News')  @else  @section('pageTitle', 'Edit News')  @endif

@section('content')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">@if(Request::path() == 'admin/news/add-news'){{ 'Add News' }} @else {{ 'Edit News' }} @endif</h2>
            </div>
        </div>
        <a href=" {{ url('admin/news') }} " class="btn btn-primary" >Back</a><br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Form Elements</div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <form name="addNewsForm" id="addNewsForm" ng-controller="newsController" enctype="multipart/form-data">
                                <input type="hidden" ng-init="newsData.newsId='@if(isset($news_id)){{$news_id}}@endif'" ng-model="newsData.newsId" />
                                <div class="form-group">
                                    <label>Title<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="title" placeholder="Title" ng-model="newsData.title">
                                </div>
                                <div class="form-group">
                                    <label>Description<span class="required">*</span></label>
                                    <textarea class="form-control" name=description" placeholder="Description" ng-model="newsData.description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Image<span class="required">@if(Request::path() == 'admin/news/add-news'){{ '*' }} @else {{ '' }} @endif</span></label>
                                    <img ng-if="newsData.image" src="{{  asset('backend/images/news/').'/' }}[[newsData.image]]" width="100" class="img-responsive img-thumbnail">
                                    <br/><br/>
                                    <input type="file" name="image" fileread="newsData.file" />
                                </div>
                                <button type="button" class="btn btn-primary" ng-click="addNews()">Submit</button>
                            </form>
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