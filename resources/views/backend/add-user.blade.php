@extends('layouts.backend.backend')
@if($userId != "")
    @section('pageTitle', 'Edit User')
@elseif($userId == "")
    @section('pageTitle', 'Add User')
@endif
@section('head')
    <link href="{{ asset('backend/css/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    <div class="row">
@if($userId != "")
        <div class="col-lg-12">
            <h2 class="page-header data-table-hdr">Edit User</h2>
        </div>
@elseif($userId == "")
        <div class="col-lg-12">
            <h2 class="page-header data-table-hdr">Add New User</h2>
        </div>
@endif
    </div>
    <a href=" {{ url('admin/users') }} " class="btn btn-primary" >Back</a><br><br>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Form Elements</div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <form name="addUserForm" id="addUserForm" ng-controller="userController">
                            <input type="hidden" ng-init="userId='<?php echo $userId; ?>'" ng-model="userId" />
                            <div class="form-group">
                                <label>First Name<span class="required">*</span></label>
                                <input type="text" class="form-control" placeholder="firstname" ng-model="userData.firstname">
                            </div>
                            <div class="form-group">
                                <label>Last Name<span class="required">*</span></label>
                                <input type="text" class="form-control" placeholder="lastname" ng-model="userData.lastname">
                            </div>
                            <div class="form-group">
                                <label>Email<span class="required">*</span></label>
                                    <input type="text" class="form-control" placeholder="email" ng-model="userData.email" <?php if($userId != ""){
                                        echo "disabled";
                                    } ?>>
                            </div>
@if($userId == "")
                            <div class="form-group">
                                <label>Password<span class="required">*</span></label>
                                    <input type="password" class="form-control" placeholder="password" ng-model="userData.password">
                            </div>
@endif
                            <div class="form-group">
                                <label>Gender<span class="required">*</span></label>
                                <div class="radio" ng-init="userData.gender=1">
                                    <label>
                                        <input type="radio" ng-model="userData.gender" value="{{ \App\Repository\usersRepo::GENDER_MALE }}" >Male
                                    </label>
                                    <label>
                                        <input type="radio" ng-model="userData.gender"  value="{{ \App\Repository\usersRepo::GENDER_FEMALE }}">Female
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Zipcode<span class="required">*</span></label>
                                <input type="text" class="form-control" placeholder="zipcode" ng-model="userData.zipcode">
                            </div>
                            <div class="form-group">
                                <label>Address<span class="required">*</span></label>
                                <input type="text" class="form-control" placeholder="address" ng-model="userData.address">
                            </div>
                            <div class="form-group">
                                <label>Status<span class="required">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" ng-model="userData.is_active" value="{{ \App\Repository\usersRepo::STATUS_INACTIVE }}" >In-Active
                                    </label>
                                    <label>
                                        <input type="radio" ng-model="userData.is_active"  value="{{ \App\Repository\usersRepo::STATUS_ACTIVE }}">Active
                                    </label>
                                </div>
                            </div>
                            <button type="button" title="Submit" class="btn btn-primary" ng-click="addNewUser()">Submit</button>
                        </form>
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