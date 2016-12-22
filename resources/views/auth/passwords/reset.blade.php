<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <title>::Gillie Network::</title>
    <link rel="icon" href="{{ asset('frontend/images/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" type="text/css">
    <style>
        body {
            height: auto;
            margin: 0;
            min-height: 100%;
            padding-bottom: 70px;
            position: relative;
        }
        footer#inner-page-footer {
            bottom: 0;
            float: none;
            height: 70px;
            left: 0;
            position: absolute;
            right: 0;
            width: 100%;
        }
        html {
            height: 100%;
            width: 100%;
        }
        .help-block {
            color: #ff0000;
            float: left;
            margin: 0;
            position: relative;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="inner-page-header">
        <div class="container">
            <div class="header-main">
                <!-- logo -->
                <div class="logo"> <a href="javascript:;"><img src="{{ asset('frontend/images/logo2.png') }}" alt="" class="inner-page-logo"></a> </div>
            </div>
            <!-- header-main ptop30 -->
        </div>
    </div>
    <div class="clear"> </div>

    <div class="fp-form ">
        <form role="form" method="POST" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">

            <p> <img src="{{ asset('frontend/images/logo-3.png') }}" alt="">
        </p>

        <div class="login-form">

            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
               {{-- <input type="text" placeholder="Email" class="email">--}}
                <input id="email" type="email" class="email" name="email" value="{{ $email or old('email') }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div><!--form-group-->
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}" >
              {{--  <input type="text" placeholder="Password" class="password">--}}
                <input id="password"  placeholder="Password" type="password" class="password" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div><!--form-group-->
            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                {{--<input type="text" placeholder="Confirm Password" class="password">--}}
                <input id="password-confirm" type="password" placeholder="Confirm Password"  class="password" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                @endif
            </div><!--form-group-->


            <input type="submit" class="gillie-btn" value="Reset Password">
        </div><!--login-form-->
            </form>
    </div><!-- login_popup -->
</div><!-- wrapper -->
@include('frontend.common.footer')



</body>



</html>