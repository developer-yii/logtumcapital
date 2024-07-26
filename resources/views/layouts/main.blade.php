<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="36x36" href="{{asset('/')}}backend/images/favicon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="{{asset('/')}}backend/images/favicon-48x48.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/')}}backend/images/apple-icon-180x180.png">

    <!-- App css -->
    <link href="{{asset('/')}}backend/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/')}}backend/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>

    {{-- jquery toast message css  --}}
    <link href="{{asset('/')}}backend/css/jquery.toast.min.css" rel="stylesheet">
    <style>
        #preloader{
            background-color: #ffffff78 !important;
        }
    </style>
    @stack('css')

</head>
<body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader"><div ></div><div ></div><div ></div></div>
        </div>
    </div>
    <!-- End Preloader-->

    <!-- Begin page -->
    <div class="wrapper">
        <!-- Left Sidebar Start  -->
        @include('partials.left-sidebar')
        <!-- Left Sidebar End -->

        <!-- Start Page Content here -->
        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('partials.topbar')
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- container -->
            </div>
            <!-- Footer Start -->
            @include('partials.footer')
            <!-- end Footer -->
        </div><!-- content -->
        <!-- End Page content -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    {{-- @include('partials.right-sidebar') --}}

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{ asset('/') }}frontend/js/jquery-3.7.1.min.js"></script>
    <script src="{{asset('/')}}backend/js/vendor.min.js"></script>
    <script src="{{asset('/')}}backend/js/app.js"></script>

    {{-- jquery toast message js --}}
    <script src="{{asset('/')}}backend/js/pages/jquery.toast.min.js"></script>

    <script src="{{asset('/')}}backend/js/pages/custom.js?{{cacheclear()}}"></script>

    @if(Session::has('status'))
        <script type="text/javascript">
            showToastMessage("success","{{ Session::get('status') }}");
        </script>
        @php Session::forget('status') @endphp
    @endif
    @if(Session::has('success'))
        <script type="text/javascript">
            showToastMessage("success","{{ Session::get('success') }}");
        </script>
        @php Session::forget('success') @endphp
    @endif
    @if(Session::has('error'))
        <script type="text/javascript">
            showToastMessage("error","{{ Session::get('error') }}");
        </script>
        @php Session::forget('error') @endphp
    @endif
    @if(Session::has('warning'))
        <script type="text/javascript">
            showToastMessage("warning","{{ Session::get('warning') }}");
        </script>
        @php Session::forget('warning') @endphp
    @endif

    @stack('js')

    @stack('pagejs')
</body>
</html>
