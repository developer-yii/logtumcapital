<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="36x36" href="{{asset('/')}}backend/images/favicon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="{{asset('/')}}backend/images/favicon-48x48.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/')}}backend/images/apple-icon-180x180.png">

    <link href="{{ asset('/') }}backend/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('/') }}backend/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}backend/css/jquery.toast.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <footer class="footer footer-alt">
        {{getFooterCopyrightText()}}
    </footer>

    <script src="{{ asset('/') }}frontend/js/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('/') }}backend/js/vendor.min.js"></script>
    <script src="{{ asset('/') }}backend/js/app.min.js"></script>
    <script src="{{ asset('/') }}backend/js/pages/jquery.toast.min.js"></script>
    <script src="{{ asset('/') }}backend/js/pages/custom.js?{{cacheclear()}}"></script>

    @stack('js')
</body>

</html>
