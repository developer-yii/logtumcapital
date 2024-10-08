@extends('layouts.app')
@section('title', __("translation.Welcome"))
@section('content')
    <!-- banner start -->
    <section id="banner" class="home">
        <div class="banner-part">
            <div class="container">
                <div class="banner-box">
                    <div class="up-title">
                        <h1>Calculo de Pago Semanal de <br>Intereses de un Préstamo</h1>
                    </div>
                    <div class="loan-sec">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="text-left">
                                    <div class="loan-box">
                                        <h2 id="current_slider_value">$14,000</h2>
                                        <div class="pro-bar">
                                            <input type="range" min="1" max="100000" value="14000"
                                                class="w-100 form-control-range range-slider" aria-valuenow="14000"
                                                aria-valuemin="1" aria-valuemax="100000">
                                        </div>
                                        <p class="title-p">{{ __("translation.With weekly payment of") }}</p>
                                        <h3 id="weekly_interest_payment">$1,162</h3>
                                        <p class="title-p">Con Requisitos Mínimos y Pagos Flexibles</p>
                                        <div class="btn-submit text-center">
                                            <a href="{{ route('register') }}">{{ __("translation.Register") }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pe-3 mx-auto">
                                    {{-- <p class="text-white fw-semibold">Importante: En Logtum Capital, únicamente ofrecemos nuestros préstamos a través de nuestra página web. No trabajamos con intermediarios externos y nunca pedimos pagos anticipados.</p> --}}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="right-img">
                                    <img src="{{ asset('frontend/images/banner-img.jpg') }}" alt="banner-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner end -->

    <!-- rate sec start -->
    <section id="interest">
        <div class="interest-part">
            <div class="container">
                <div class="interest-box">
                    <div class="title-text">
                        <h2><span>{{ __("translation.Comparison of") }}</span> {{ __("translation.Annual interest Rates") }}</h2>
                        {{-- <h2><span>Comparacion de </span> Tasas de Intres Anual</h2> --}}
                    </div>
                    <div class="rate-box">
                        <div class="row">
                            @php
                                $companyLogos = [asset('frontend/images/LOGO.png'), asset('frontend/images/CITIBANAMEX_LOGO.png'), asset('frontend/images/BBVA_LOGO.png')];
                                $i = 0;
                            @endphp
                            @foreach($interestRateData as $interest)
                                <div class="col-md-4">
                                    <div class="box-box">
                                        @if($interest->id == 1)
                                            <div class="img-box">
                                                <img src="{{ $companyLogos[$i] }}" alt="logo-img">
                                            </div>
                                        @else
                                            <div class="logoText">
                                                <h3 class="{{(($interest->id % 2) == 0)?'red':'blue'}}">{{ $interest->company_name }}</h3>
                                                <p>{{ $interest->sub_title }}</p>
                                            </div>
                                        @endif
                                        <div class="text-box">
                                            <h4>{{ $interest->interest_rate.'%' }}</h4>
                                            <p>{{ __("translation.Fixed Annual Rate") }}</p>
                                            <div class="value-btn">
                                                <button class="btn-val">CAT Por Pago Aplazado</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            {{-- <div class="col-md-4">
                                <div class="box-box">
                                    <div class="img-box">
                                        <img src="{{ $companyLogos[1] }}" alt="compant2">
                                    </div>
                                    <div class="text-box">
                                        <h4>72.80%</h4>
                                        <p>Fixed Annual Rate</p>
                                        <div class="value-btn">
                                            <button class="btn-val">Evaluate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box-box">
                                    <div class="img-box">
                                        <img src="{{ $companyLogos[2] }}" alt="compant1">
                                    </div>
                                    <div class="text-box">
                                        <h4>71.10%</h4>
                                        <p>Fixed Annual Rate</p>
                                        <div class="value-btn">
                                            <button class="btn-val">Evaluate</button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="linkBox">
                        <div class="linkClick">
                            {!! $settingData->value !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- rate sec end -->

    <!-- approve sec start -->
    <section id="approve">
        <div class="approve-part">
            <div class="container">
                <div class="approve-box">
                    <div class="receive-bx">
                        <div class="main-box">
                            <div class="title-box">
                                <h2>Proceso De Aprobacion</h2>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="send-box down-box first">
                                        <h2><span>1</span></h2>
                                        <h5>Envie</h5>
                                        <p>Envíe la cantidad requerida y su documentación</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="send-box down-box second">
                                        <h2><span>2</span></h2>
                                        <h5>Reciba</h5>
                                        <p>Propuesta de aprobación en cuestión de minutos</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="send-box third-box">
                                        <h2><span>3</span></h2>
                                        <h5>Disfruta</h5>
                                        <p>Disposición de recursos en beneficio de los colaboradores</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <img src="{{ asset('frontend/images/LOAN_APPROVAL.png') }}"> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- approve sec end -->

    <!-- our-services sec start -->
    <section id="services">
        <div class="services-part">
            <div class="container">
                <div class="servicesbox">
                    <div class="our-box">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="left-box">
                                    <h2>{{ __("translation.Our Services") }}</h2>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="right-box">
                                    <p>{{ __("translation.We offer convenient money lending solution and reliable. With competitive interest rates that are lower than traditional banks. our goal is to make the loan is accessible to everyone.") }}</p>
                                    <div class="reg-btn">
                                        <a href="{{ route('register') }}">Registre Su Empresa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loans-type">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>{{ __("translation.Personal Loan") }}</h4>
                                    <h6>Necesita Fondos Para:</h6>
                                    <ul>
                                        <li>
                                            <p>Un Proyecto Personal</p>
                                        </li>
                                        <li>
                                            <p>{{ __("translation.unexpected expense, or") }}</p>
                                        </li>
                                        <li>
                                            {{-- <p>{{ __("translation.special occasion?") }}</p> --}}
                                            <p>Ocasión Especial</p>
                                        </li>
                                    </ul>
                                    <p class="lastLine">{{ __("translation.Our personal loans offer competitive interest rates.") }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>{{ __("translation.Emergency Loan") }}</h4>
                                    <h6>{{ __("translation.Life is full of surprises") }}</h6>
                                    <ul>
                                        <li>
                                            <p>{{ __("translation.Medical bills") }}</p>
                                        </li>
                                        <li>
                                            <p>{{ __("translation.Car repairs") }}</p>
                                        </li>
                                        <li>
                                            <p>{{ __("translation.Home emergencies") }}</p>
                                        </li>
                                    </ul>
                                    <p class="lastLine">{{ __("translation.We understand the urgency of your situation and strive to offer fast financing.") }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>{{ __("translation.Salary Advances") }}</h4>
                                    <h6>{{ __("translation.Our salary advances will help you:") }}</h6>
                                    <ul>
                                        <li>
                                            <p>{{ __("translation.Bridge the gap between paydays") }}</p>
                                        </li>
                                        <li>
                                            <p>{{ __("translation.Reduce financial stress") }}</p>
                                        </li>
                                        <li>
                                            <p>{{ __("translation.Access funds to cover expenses") }}</p>
                                        </li>
                                    </ul>
                                    <p class="lastLine">{{ __("translation.Say goodbye to financial stress and hello to peace of mind with our salary advance services.") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- our-services sec end -->
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var weeklyInterestRate = 60.32 / 52.143;
            var weeklyInterestPayment = 0;

            $('ul li a').on('click', function(e) {
                // Remove 'active' class from all links
                $('ul li a').removeClass('active');

                // Add 'active' class to the clicked link
                $(this).addClass('active');
            });

            $('.range-slider').on('input', function() {
                var sliderValue = $(this).val();
                $('h2#current_slider_value').html('$' + parseFloat(sliderValue).toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));

                weeklyInterestPayment = Math.round((sliderValue * weeklyInterestRate) / 100);
                $('h3#weekly_interest_payment').html('$' + weeklyInterestPayment.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }));
            });

        });
    </script>
@endpush
