<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{{ csrf_token() }}">
    <title>Gillie Network - @yield ('pageTitle')</title>
    <link href="{{ asset('common/css/textAngular.css') }}" rel="stylesheet">

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('common/css/gritter_toastr.css') }}" rel="stylesheet">


    <!--[if lt IE 9]>
    <script src="{{ asset('backend/js/html5shiv.js') }}"></script>
    <script src="{{ asset('backend/js/respond.min.js') }}"></script>
    <![endif]-->

    @yield('head')
</head>

<body ng-cloak  ng-app="gillieNetworkApp">
<span ng-show="loading"  id="gille_loader"></span>
