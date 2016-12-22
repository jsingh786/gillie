
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <meta name="_token" content="{{ csrf_token() }}">
    <title>::GillieNetwork::</title>
    <link rel="icon" href="{{ asset('frontend/images/favicon.ico') }}" type="image/x-icon" />
    <link rel='stylesheet'  href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel="stylesheet" href="{{ asset('frontend/js/jquery-ui-1.12.0.custom/jquery-ui.min.css') }}">
    <link href="{{ asset('common/css/font-awesome.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsiveslides.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/hover.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/ng-dialog.css') }}">
    <link href="{{ asset('common/css/gritter_toastr.css') }}" rel="stylesheet">


    <!----------------------------------- JS Files ---------------------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    <script src="{{ asset('common/js/jquery-1.11.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.4/angular.js"></script>
    <script src="{{ asset('frontend/js/responsiveslides.min.js') }}"></script>

    <script src="{{ asset('common/js/constants.js') }}"></script>
    <script src="{{ asset('frontend/js/angular/frontendApp.js') }}"></script>
    <script src="{{ asset('frontend/js/angular-animate.min.js') }}"></script>
    <script src="{{ asset('frontend/js/ng-dialog.js') }}"></script>
    <script src="{{ asset('frontend/js/dirPagination.js') }}"></script>
    <script src="{{ asset('frontend/js/constants.js') }}"></script>

    <!-- angular ui date picker js -->
    <script src="{{ asset('frontend/js/jquery-ui-1.12.0.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('common/js/common.js') }}"></script>
    <script src="{{ asset('frontend/js/date.js') }}"></script>
    <!-- angular ui date picker js END-->

    <script src="{{ asset('frontend/js/dirPagination.js') }}"></script>
    <script src="{{ asset('frontend/js/angular-timeago.js') }}"></script>
    <script src ="{{ asset('frontend/js/angular/star-directive.js') }}"></script>
    <script src="{{ asset('common/js/gritter_toastr.js') }}"></script>

    <script src="{{ asset('frontend/js/angular/controllers/profileRightMenuController.js') }}" ></script>
    <script src="{{ asset('frontend/js/angular/controllers/profileLeftMenuController.js') }}" ></script>
    <script src="{{ asset('frontend/js/profileLeftMenu.js') }}" ></script>
    <script src="{{ asset('frontend/js/angular/controllers/profileHeaderController.js') }}" ></script>
    <script src="{{ asset('frontend/js/angular/controllers/FollowingController.js') }}" ></script>

    {{--TextAnglar editor files---------------------------------------------------------------------------}}
    {{--font-awesome.min.css is required for this and added somewhere above in this file.--}}
    {{--bootstrap.min.css is required for this and added somewhere above in this file.--}}
    <link rel='stylesheet'  href='{{ asset('common/css/textAngular.css') }}'>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/textAngular/1.5.0/textAngular-rangy.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/textAngular/1.5.0/textAngular-sanitize.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/textAngular/1.5.0/textAngular.min.js'></script>
    {{--End TextAnglar editor files------------------------------------------------------------------------}}



{{--    <script>

        // Enable pusher logging - don't include this in production
        //Pusher.logToConsole = true;

        var pusher = new Pusher('68fdc4db98a39b3ee72c', {
            encrypted: true
        });

        var channel = pusher.subscribe('test_channel');
        console.log(channel);
        channel.bind('my_event', function(data) {
            console.log(data);
        });
    </script>--}}
