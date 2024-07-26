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
    <title>{{ config('app.name') }} | Welcome</title>
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
                        <a href="#"><img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img"></a>
                    </div>
                    <div class="main-nav">
                        <nav>
                            <ul>
                                <li>
                                    <a href="#" class="active">Services</a>
                                </li>
                                <li>
                                    <a href="#">How does it work</a>
                                </li>
                                <li>
                                    <a href="#">Loan Calculator</a>
                                </li>
                                <li>
                                    <a href="#">Contact</a>
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

    <!-- banner start -->
    <section id="banner" class="home">
        <div class="banner-part">
            <div class="container">
                <div class="banner-box">
                    <div class="up-title">
                        <h1>Calculate the payment <br>monthly of your loan</h1>
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
                                        <h3 id="weekly_interest_payment">$162</h3>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste beatae
                                            exercitationem praesentium eveniet vero fugit placeat ducimus quidem
                                            cupiditate.</p>
                                        <div class="btn-submit">
                                            <a href="#">Submit</a>
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
                        <h2><span>Comparison</span> of Annual interest Rates</h2>
                    </div>
                    <div class="rate-box">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box-box">
                                    <img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img">
                                    <h4>60.32%</h4>
                                    <p>Fixed Annual Rate</p>
                                    <div class="value-btn">
                                        <button class="btn-val">Evaluate</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box-box">
                                    <img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img">
                                    <h4>60.32%</h4>
                                    <p>Fixed Annual Rate</p>
                                    <div class="value-btn">
                                        <button class="btn-val">Evaluate</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="box-box">
                                    <img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img">
                                    <h4>60.32%</h4>
                                    <p>Fixed Annual Rate</p>
                                    <div class="value-btn">
                                        <button class="btn-val">Evaluate</button>
                                    </div>
                                </div>
                            </div>
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
                    <div class="title-box">
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
                    </div>
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
                                        <a href="#">Register</a>
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
                                    <h6>You need funds for a</h6>
                                    <ul>
                                        <li>
                                            <p>Personal project</p>
                                        </li>
                                        <li>
                                            <p>Unexpected expense</p>
                                        </li>
                                        <li>
                                            <p>Special occasion</p>
                                        </li>
                                    </ul>
                                    <p>Our loans personal offers rates of competitive interest</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>Emergency Loan</h4>
                                    <h6>Life is full of surprises</h6>
                                    <ul>
                                        <li>
                                            <p>Personal project</p>
                                        </li>
                                        <li>
                                            <p>Unexpected expense</p>
                                        </li>
                                        <li>
                                            <p>Special occasion</p>
                                        </li>
                                    </ul>
                                    <p>Our loans personal offers rates of competitive interest</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="personal-box">
                                    <h4>Salary Advances</h4>
                                    <h6>Our salary advances will help you</h6>
                                    <ul>
                                        <li>
                                            <p>Personal project</p>
                                        </li>
                                        <li>
                                            <p>Unexpected expense</p>
                                        </li>
                                        <li>
                                            <p>Special occasion</p>
                                        </li>
                                    </ul>
                                    <p>Our loans personal offers rates of competitive interest</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- our-services sec end -->

    <!-- footer start -->
    <footer>
        <div class="footer-part">
            <div class="container">
                <div class="footer-box">
                    <div class="ft-logo">
                        <a href="#"><img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo-img"></a>
                    </div>
                    <div class="ft-menu">
                        <ul>
                            <li>
                                <a href="#">Services</a>
                            </li>
                            <li>
                                <a href="#">How does it work</a>
                            </li>
                            <li>
                                <a href="#">Loan Calculator</a>
                            </li>
                            <li>
                                <a href="#">Contact</a>
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
    <script>
        $(document).ready(function() {
            var weeklyInterestRate = 60.32 / 52.143;
            var weeklyInterestPayment = 0;

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
</body>

</html>
