<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <link rel="icon" type="image/png" sizes="36x36" href="{{asset('/')}}backend/images/favicon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="{{asset('/')}}backend/images/favicon-48x48.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/')}}backend/images/apple-icon-180x180.png">
    <title>{{ config('app.name') }} | @yield('title')</title>
</head>

<body>
    <!-- header start -->
    <header>
        <div class="top-header">
            <div class="container">
                <div class="reg-box">
                    <div class="mail-box">
                        <a href="mailto:info.logtum@gmail.com">info.logtum@gmail.com</a>
                    </div>
                    <div class="log-box">
                        <ul>
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><span class="text-white">|</span></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-part">
            <div class="container">
                <div class="header-box">
                    <div class="logo-box">
                        <a href="{{ url('/') }}"><img src="{{ asset('frontend/images/LOGO.png') }}" alt="logo-img"></a>
                    </div>
                    <div class="main-nav">
                        <nav>
                            <ul>
                                <li>
                                    <a href="{{ url('/').'#services'}}">Services</a>
                                </li>
                                <li>
                                    <a href="{{ url('/').'#approve'}}">How it works</a>
                                </li>
                                <li>
                                    <a href="{{ url('/').'#banner' }}">Loan Calculator</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}" class="{{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">Contact</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="click-bar">
                            <i class="fa-solid fa-bars click-menu"></i>
                            <i class="fa-solid fa-xmark cancel-menu"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->

    @yield('content')

    <footer>
        <div class="footer-part">
            <div class="container">
                <div class="footer-box">
                    <div class="ft-logo">
                        <a href="#"><img src="{{ asset('frontend/images/LOGO.png') }}" alt="logo-img"></a>
                    </div>
                    <div class="ft-menu">
                        <ul>
                            <li>
                                <a href="{{ url('/').'#services'}}">Services</a>
                            </li>
                            <li>
                                <a href="{{ url('/').'#approve'}}">How it works</a>
                            </li>
                            <li>
                                <a href="{{ url('/').'#banner'}}">Loan Calculator</a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="icon-box">
                        <ul>
                            <li><a href="https://www.facebook.com/profile.php?id=61564223813658" target="_blank"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="https://www.instagram.com/logtumcapital/" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                            {{-- <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li> --}}
                            {{-- <li><a href="#"><i class="fa-brands fa-telegram"></i></a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->
</body>
    <script src="{{ asset('frontend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    @stack('js')
</html>
