@extends('layouts.backend.backend')
@section('head')
    <link href="{{ asset('backend/css/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/user_management.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active">Icons</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">User Management</h1>
        </div>
    </div>
    {{--Starting HTML of datatables for users listing.--}}
    <div id="ilook_dt_wrapper" class="dataTables_wrapper">
        <div class="header_user_mgmt">
            <div class="dataTables_length" id="jquery_dt_length">
                <label>Show <select name="jquery_dt_length" aria-controls="jquery_dt" class="">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                        <option value="500">500</option></select> entries
                </label>
                <span class = "buttons">
                    <span title="Click to Delete selected users" id = "delete_records">Delete Selected User(s)</span>
                </span>
            </div>
            <div id="jquery_dt_filter" class="dataTables_filter">
                <label>Search:<input type="search" class="" placeholder="" aria-controls="jquery_dt"></label>
            </div>
        </div>
        <div id="jquery_dt_processing" class="dataTables_processing"
             style="display: none; margin-top:40px;">Processing...</div>



        <table id="jquery_dt" class="display dataTable" width="100%"
               cellspacing="0" cellpadding="0" border="0" role="grid"
               aria-describedby="jquery_dt_info" style="width: 100%;">
            <thead>
                <tr role="row">
                    {{----------------------------------------------------------------------------------------------------------}}
                    {{----db_column attribute is set according to doctrine2 query requirement. Please see query in repo class.--}}
                    {{----------------------------------------------------------------------------------------------------------}}
                    <th style="width: 2%; text-align:left; padding: 10px 9px;">
                        <input type = "checkbox" name = "master_delete_checkbox" class = "master_delete_cb">
                    </th>
                    <th class="sorting sorting_asc active" tabindex="0"
                        aria-controls="jquery_dt" rowspan="1" colspan="1"
                        style="width: 3%;" aria-sort="DESC"
                        db_column="usr.id" db_column_alias="idd" position = "2">
                        #
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="jquery_dt"
                        rowspan="1" colspan="1" style="width: 30%;" aria-sort="ASC"
                        db_column='usr.firstname' db_column_alias="first_name"  position = "3">First Name</th>
                    <th  class="sorting" tabindex="0" aria-controls="jquery_dt"
                         rowspan="1" colspan="1" style="width:30%;" aria-sort="ASC"
                         db_column='usr.lastname' db_column_alias="last_name" position = "4">Last Name</th>
                    <th  class="sorting" tabindex="0" aria-controls="jquery_dt"
                         rowspan="1" colspan="1" style="" aria-sort="ASC"
                         db_column='usr.email' db_column_alias="email" position = "5">Email</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th rowspan="1" colspan="1"></th>
                    <th rowspan="1" colspan="1">#</th>
                    <th rowspan="1" colspan="1">First Name</th>
                    <th rowspan="1" colspan="1">Last Name</th>
                    <th rowspan="1" colspan="1">Email</th>
                </tr>
            </tfoot>
        </table>

        {{--This division will show label status for e.g. "Showing 1 to 50 of 61 entries" --}}
        <div class="dataTables_info" id="jquery_dt_info" role="status"
             aria-live="polite"></div>

        {{--This division is for pagination rendering.--}}
        <div class="dataTables_paginate paging_simple_numbers"
             id="jquery_dt_paginate">
            <a class="paginate_button previous disabled" aria-controls="jquery_dt"
               data-dt-idx="" tabindex="0" id="jquery_dt_previous">Previous</a>
				<span id = "page_buttons">
				</span>
            <a class="paginate_button next" aria-controls="jquery_dt"
               data-dt-idx="" tabindex="0" id="jquery_dt_next">Next</a>
        </div>
    </div>
</div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('backend/js/user_management.js') }}" ></script>
@endsection