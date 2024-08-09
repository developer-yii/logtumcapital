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
    <title>{{ config('app.name') }} | Contact</title>
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
                        <a href="{{ url('/') }}"><img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img"></a>
                    </div>
                    <div class="main-nav">
                        <nav>
                            <ul>
                                <li>
                                    <a href="{{ url('/').'#services' }}">Services</a>
                                </li>
                                <li>
                                    <a href="{{ url('/').'#approve' }}">How it works</a>
                                </li>
                                <li>
                                    <a href="{{ url('/').'#banner' }}">Loan Calculator</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}" class="{{ Request::routeIs('contact') ? 'active' : '' }}">Contact</a>
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

    <!-- innaerBanner start -->
    <section class="innaerBanner over-bg">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content text-center">
                        <h2>Contáctenos</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="list-item"><a href="{{ url('/') }}">Inicio</a></li>
                                <li class="list-item active" aria-current="page">Contáctenos</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- innaerBanner end -->

    <!-- footer start -->
    <section class="contact-area pt-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6">
                    <div class="contact-info-custom">
                        <div class="section-title text-left mb-25">
                            <span class="sub-title">Información de Contacto</span>
                            <h2 class="title">Nuestra Dirección de Soporte</h2>
                            <div class="line"><img src="{{ asset('/frontend/images/title_line.png') }}" alt=""></div>
                        </div>
                        <p>No dude en contactarnos en cualquier momento. Es nuestro placer servirle.</p>
                        <ul class="contact-info-list">
                            <li>
                                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="content">
                                    <h5>Dirección de la empresa</h5>
                                    <p>64500, Ernesto Garcia Ortiz 154, Del Nte., 64500 Monterrey, N.L.</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="content">
                                    <h5>Número de teléfono</h5>
                                    <p><a href="tel:+528183518600">+52 81 8351 8600</a> (Mexico)</p>
                                    <p><a href="tel:+17789948531">+1 778 994 8531</a> (Canada)</p>
                                </div>
                            </li>
                            <li>
                                <div class="icon"><i class="far fa-envelope"></i></div>
                                <div class="content">
                                    <h5>Contáctenos</h5>
                                    <p><a href="mailto:info.logtum@gmail.com">info.logtum@gmail.com</a></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6">
                    {{-- <div class="contact-form-wrap-box">
                        <div class="login-wrap" style="margin-bottom: 0;">
                            <h3 class="get-title">Envía Tu <span>Solicitud</span></h3>
                            <form action="" class="login-form">
                            	<input type="hidden" name="_token" value="v3lswbj4AbVs7BJgx6XZ2O4QB3BsHAZeIZS1wB2b" autocomplete="off">
                                <div class="form-grp">
                                    <label for="name">Tu Nombre <span>*</span></label>
                                    <input type="text" placeholder="Escribe tu nombre">
                                </div>
                                <div class="form-grp">
                                    <label for="email">Tu Correo <span>*</span></label>
                                    <input type="email" placeholder="Escribe tu correo">
                                </div>
                                <div class="form-grp">
                                    <label for="message">Tu Mensaje <span>*</span></label>
                                    <textarea name="message" placeholder="Escribe un mensaje"></textarea>
                                </div>
                                <button type="submit" class="btn">Enviar</button>
                            </form>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- footer start -->

    <!-- footer start -->
    <footer>
        <div class="footer-part">
            <div class="container">
                <div class="footer-box">
                    <div class="ft-logo">
                        <a href="#"><img src="{{ asset('frontend/images/logo-img2.png') }}" alt="logo-img"></a>
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
                                <a href="{{ route('contact') }}" class="{{ Request::routeIs('contact') ? 'active' : '' }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="icon-box">
                        <ul>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-telegram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->



    <script src="{{ asset('frontend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
</body>

</html>
