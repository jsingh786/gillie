@extends('layouts.backend.backend')
@section('pageTitle', 'View User')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header data-table-hdr">View User</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Form Elements</div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <div ng-controller="userController" ng-cloak>
                            <form class="form-horizontal" name="viewUserFrm" >
                                <input type="hidden" ng-init="userId='<?php echo $userId; ?>'" ng-model="userId" />
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Firstname:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.firstname]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Lastname:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.lastname]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Email:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.email]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Gender:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.gender_name]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Zipcode:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.zipcode]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Address:</label>
                                    <div class="col-sm-10">
                                        <p style="word-wrap:break-word !important;" class="form-control-static">[[userData.address]]</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword" class="col-sm-2 control-label">Status:</label>
                                    <div class="col-sm-10">
                                        <p class="form-control-static">[[userData.status]]</p>
                                    </div>
                                </div>

                                <a class="btn btn-default" href="{{url('admin/users')}}" type="reset">Back</a>
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
    <script src="{{ asset('backend/js/angular/controllers/userController.js') }}"></script>
@endsection