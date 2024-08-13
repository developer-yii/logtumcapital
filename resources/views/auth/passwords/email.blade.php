@extends('layouts.login')
@section('title', 'Forgot Password')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5">
            <div class="card">
                <!-- Logo -->
                <div class="card-header py-4 text-center bg-primary">
                    <a href="index.html">
                        <span><img src="{{ asset('frontend/images/LOGO.png') }}" alt="logo" height="50"></span>
                    </a>
                </div>

                <div class="card-body p-4">

                    <div class="text-center w-75 m-auto">
                        <h4 class="text-dark-50 text-center mt-0 fw-bold">Reset Password</h4>
                        <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to
                            reset your password.</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail Address<span class="text-danger"> *</span></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" required="" placeholder="Enter e-mail address">
                            @error('email')
                                <span class="error text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-0 text-center">
                            <button class="btn btn-primary" type="submit">Reset Password</button>
                        </div>
                    </form>
                </div> <!-- end card-body-->
            </div>
            <!-- end card -->

            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="text-muted">Back to <a href="{{ route('login') }}" class="text-muted ms-1"><b>Log In</b></a></p>
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- end col -->
    </div>
@endsection
