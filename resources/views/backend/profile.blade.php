@extends('layouts.backend.backend')
@section('pageTitle', 'My Profile')
@section('content')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">


        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">My Profile</h2>
            </div>
        </div><!--/.row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile Details</div>
                    <div class="panel-body" ng-controller="editProfileController ">
                        <div class="col-md-6">
                            <form role="form" >
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" placeholder="Enter your first name."  ng-model="adminDetail.firstname"  >
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" placeholder="Enter your last name."   ng-model="adminDetail.lastname" >
                                </div>
                                <button class="btn btn-default" ng-click="editDetail()" title="Update">Update</button>
                            </form>
                        </div>
                    </div>
                    <div class="panel-heading">Change Password</div>
                    <div class="panel-body" ng-controller="changePasswordController">
                        <div class="col-md-6">
                            <form role="form" >
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" class="form-control" name="old_password" ng-model="passwordDetail.old_password" placeholder="Enter your old password">
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="password" ng-model="passwordDetail.password" placeholder="Enter your new password.">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control"  name="confirm_password" ng-model="passwordDetail.confirm_password" placeholder="Re-enter your new password.">
                                </div>
                                <button class="btn btn-default" ng-click="changePassword()">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.col-->
        </div><!-- /.row -->
    </div>


@endsection

@section('footer')
    <script src="{{ asset('backend/js/angular/controllers/editProfileController.js') }}"></script>
    <script src="{{ asset('backend/js/angular/controllers/changePasswordController.js') }}"></script>
@endsection