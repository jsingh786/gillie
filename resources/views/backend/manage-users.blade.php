@extends('layouts.backend.backend')
@section('pageTitle', 'Manage Users')
@section('head')
   <link href="{{ asset('backend/css/datatables.css') }}" rel="stylesheet">
@endsection
@section('content')
<div ng-controller="userController" ng-cloak>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header data-table-hdr">Manage Users</h2>
            </div>
        </div>
        <a href="{{ url('admin/users/add-user') }}" title="Add User" class="btn btn-primary" type="submit">Add User</a>
        <div class="dataTables_wrapper">
            <div  class="dataTables_filter">
                <label>Search:</label>
                <span><input class="form-control" type="text"  ng-keyup="rerenderTable()" ng-model="searchParam"></span>
            </div>
            <table datatable=""  dt-options="dtOptions" dt-columns="dtColumns" dt-instance="dtInstance" class="table table-striped table-bordered table-hover">
            </table>
        </div>
    </div>
</div>
@endsection
@section('footer')

    <script src="{{ asset('backend/js/angular/controllers/userController.js') }}"></script>
@endsection