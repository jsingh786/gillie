@include('layouts.backend.header')

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><span>Gillie Network</span> Admin Panel</a>
            <ul class="user-menu">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle " data-toggle="dropdown"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> {{ Auth::guard('admin')->user()->getFirstname() }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li style="margin-left: 3px;"><a href="{{ url('admin/myprofile') }}"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>My Profile</a></li>
                        <li><a href="{{ url('admin/logout') }}"><svg class="glyph stroked cancel"><use xlink:href="#stroked-cancel"></use></svg> Logout</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-brand pull-right" href="#"><span>Welcome</span></div>
        </div>

    </div>
</nav>


<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <ul class="nav menu">

        <li <?php if(Request::path() == "admin/dashboard") {echo 'class="active"'; } ?> ><a href="{{ url('/admin/dashboard') }}"> <i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;Dashboard</a></li>
        <li <?php if(Request::is("admin/users/*") || Request::is("admin/users")) {echo 'class="active"'; } ?>><a href="{{ url('/admin/users') }}"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp; Manage Users </a></li>
        <li <?php if(Request::is("admin/cms/*") || Request::is("admin/cms")) {echo 'class="active"'; } ?>><a href="{{ url('/admin/cms') }}"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;&nbsp; CMS </a></li>
        <li <?php if(Request::is("admin/news/*") || Request::is("admin/news")) {echo 'class="active"'; } ?>><a href="{{ url('/admin/news') }}"> <i class="glyphicon glyphicon-globe"></i>&nbsp;&nbsp;Manage News </a></li>
        <li <?php if(Request::is("admin/banner/*") || Request::is("admin/banner")) {echo 'class="active"'; } ?>><a href="{{ url('/admin/banner') }}"> <i class="glyphicon glyphicon-th-large"></i>&nbsp;&nbsp;Manage Banners </a></li>
        <li <?php if(Request::is("admin/rating/*") || Request::is("admin/rating")) {echo 'class="active"'; } ?>><a title="Manage Profile Rating" href="{{ url('/admin/rating') }}"><i class="glyphicon glyphicon-star"></i>&nbsp;&nbsp;Manage Profile Rating </a></li>
        <!--li><a href="{{ url('/admin/userManagement') }}"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg> Users Management</a></li-->

    </ul>

</div>

@yield('content')
@include('layouts.backend.footer')