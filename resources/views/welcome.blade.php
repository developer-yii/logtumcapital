@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
    <!-- banner start -->
    <section id="banner" class="home">
        <div class="banner-part">
            <div class="container">
                <div class="banner-box">
                    <div class="up-title">
                        <h1>Calculate the monthly <br>payment of your loan</h1>
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
                                        <p class="title-p">With weekly payment of</p>
                                        <h3 id="weekly_interest_payment">$1,162</h3>
                                        <p>You only need your ID and a proof of address</p>
                                        <div class="btn-submit text-center">
                                            <a href="{{ route('register') }}">Register</a>
                                        </div>
                                    </div>
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
                        <h2><span>Comparison of</span> Annual interest Rates</h2>
                    </div>
                    <div class="rate-box">
                        <div class="row">
                            @php
                                $companyLogos = [asset('frontend/images/LOGTUM_INTREST_RATE_LOGO.png'), asset('frontend/images/CITIBANAMEX_LOGO.png'), asset('frontend/images/BBVA_LOGO.png')];
                                $i = 0;
                            @endphp
                            @foreach($interestRateData as $interest)
                                <div class="col-md-4">
                                    <div class="box-box">
                                        <div class="img-box">
                                            <img src="{{ $companyLogos[$i] }}" alt="logo-img">
                                        </div>
                                        <div class="text-box">
                                            <h4>{{ $interest->interest_rate.'%' }}</h4>
                                            <p>Fixed Annual Rate</p>
                                            <div class="value-btn">
                                                <button class="btn-val">Evaluate</button>
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
                    {{-- <div class="title-box">
                        <h2>Loan Approval Process</h2>
                    </div>
                    <div class="receive-bx">
                        <div class="main-box">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="send-box down-box">
                                        <h2><span>1</span></h2>
                                        <h5>Send</h5>
                                        <p>Documentation, Photo and video of your inventory</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="send-box down-box">
                                        <h2><span>2</span></h2>
                                        <h5>Receives</h5>
                                        <p>Proposal for approval in minutes.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="send-box third-box">
                                        <h2><span>3</span></h2>
                                        <h5>Enjoy</h5>
                                        <p>From the fund in your account and grow your business.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <img src="{{ asset('frontend/images/LOAN_APPROVAL.png') }}">
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
                                    <h2>Our Services</h2>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="right-box">
                                    <p>We offer convenient money lending solution and reliable. With competitive
                                        interest rates that are lower than traditional banks. our goal is to make the
                                        loan is accessible to everyone.</p>
                                    <div class="reg-btn">
                                        <a href="{{ route('register') }}">Register</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loans-type">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>Personal Loan</h4>
                                    <h6>Need funds for</h6>
                                    <ul>
                                        <li>
                                            <p>a personal project,</p>
                                        </li>
                                        <li>
                                            <p>unexpected expense, or</p>
                                        </li>
                                        <li>
                                            <p>special occasion?</p>
                                        </li>
                                    </ul>
                                    <p>Our personal loans offer competitive interest rates.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>Emergency Loan</h4>
                                    <h6>Life is full of surprises</h6>
                                    <ul>
                                        <li>
                                            <p>Medical bills</p>
                                        </li>
                                        <li>
                                            <p>Car repairs</p>
                                        </li>
                                        <li>
                                            <p>Home emergencies</p>
                                        </li>
                                    </ul>
                                    <p>We understand the urgency of your situation and strive to offer fast financing.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>Salary Advances</h4>
                                    <h6>Our salary advances will help you:</h6>
                                    <ul>
                                        <li>
                                            <p>Bridge the gap between paydays</p>
                                        </li>
                                        <li>
                                            <p>Reduce financial stress</p>
                                        </li>
                                        <li>
                                            <p>Access funds to cover expenses</p>
                                        </li>
                                    </ul>
                                    <p>Say goodbye to financial stress and hello to peace of mind with our salary advance services.</p>
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
