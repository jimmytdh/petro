<?php $user = \Illuminate\Support\Facades\Session::get('user'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ (isset($title)) ? $title: 'TDH PETRO' }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('/back/bower_components') }}/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/back/bower_components') }}/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('/back/bower_components') }}/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/back') }}/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url('/back') }}/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{{ asset('/back/plugins/Lobibox/lobibox.css') }}">
    <link rel="stylesheet" href="{{ asset('/back/css/style.css') }}">

    <link rel="icon" type="image/png" href="{{ url('img/logo-white.png') }}">
@yield('css')
<!-- Google Font -->
    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            {{--<span class="logo-mini"><b>DTS</b></span>--}}
            <span class="logo-mini"><img src="{{ url('img/logo-white.png') }}" width="32px" /></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="{{ url('img/logo-white.png') }}" width="32px" /> <b>TDH</b> PETRO</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ url('img/logo-white.png') }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">Welcome, {{ $user->fname }} {{ $user->lname }}</span>
                        </a>
                        <ul class="dropdown-menu hidden">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ url('img/logo-white.png') }}" class="img-circle" alt="User Image">

                                <p>
                                    Welcome, {{ $user->fname }} {{ $user->lname }}                     
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="#changePassword" data-toggle="modal" class="btn btn-default btn-flat btn-sm">Change Password</a>
                                    <a href="{{ url('logout') }}" class="btn btn-default btn-flat btn-sm">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
@include('menu')

<!-- Content Wrapper. Contains page content -->
@yield('content')
<!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.1
        </div>
        <strong><a href="#">TDH Tracking</a>.</strong> All rights
        reserved.
    </footer>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@yield('modal')
<!-- jQuery 3 -->
@include('modal.accept')
@include('modal.password')
@include('modal.track')
<script src="{{ url('/back/bower_components') }}/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('/back/bower_components') }}/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/back/bower_components') }}/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('back/plugins/Lobibox/Lobibox.js') }}?v=1"></script>


<!-- AdminLTE App -->
<script src="{{ url('/back') }}/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- AdminLTE for demo purposes -->
<script src="{{ url('/back') }}/js/demo.js"></script>
@include('script.lobibox')
@include('script.track')
@yield('js')
</body>
</html>
