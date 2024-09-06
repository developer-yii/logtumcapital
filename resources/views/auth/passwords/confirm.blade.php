@extends('layouts.login')
@section('title', __("translation.Confirm Password"))
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
                    <h4 class="text-dark-50 text-center mt-0 fw-bold">{{ __('translation.Confirm Password') }}</h4>
                    <p class="text-muted mb-4">{{ __('translation.Please confirm your password before continuing.') }}</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('translation.Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('translation.Confirm Password') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link p-0 pt-2" href="{{ route('password.request') }}">
                                    {{ __('translation.Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
