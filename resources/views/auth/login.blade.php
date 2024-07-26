@extends('layouts.login')
@section('title', 'Log In')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-5">
            <div class="card">

                <div class="card-header py-4 text-center bg-primary">
                    <a href="{{ route('login') }}">
                        <span><img src="{{ asset('frontend/images/logo-img.png') }}" alt="logo" height="50"></span>
                    </a>
                </div>

                <div class="card-body p-4">

                    <div class="text-center w-75 m-auto">
                        <h4 class="text-dark-50 text-center pb-0 fw-bold">Log In</h4>
                        <p class="text-muted mb-4">Enter your email address and password to access your account.</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST" id="login_form">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail Address</label>
                            <input class="form-control  @error('email') is-invalid @enderror" type="email" id="email" name="email" required="" placeholder="Enter e-mail address" value="{{ old('email') }}" autocomplete>
                            @error('email')
                                <span class="error text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('password.request') }}" class="text-muted float-end">
                                <small>Forgot your password?</small>
                            </a>
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required="" autocomplete>
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="error text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 mb-0 text-center">
                            <button class="btn btn-primary" type="submit" id="login_submit_btn">Log In</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
