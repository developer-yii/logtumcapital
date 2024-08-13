@extends('layouts.app')
@section('title', 'Contact')
@section('content')
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
@endsection
