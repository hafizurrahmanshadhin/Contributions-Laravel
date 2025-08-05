@extends('backend.app')

@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<style>
    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap");

    body {
        background-color: #fffff0;
        box-sizing: border-box;
        overflow-x: hidden;
        scroll-behavior: smooth;
        font-family: "Open Sans", sans-serif !important;
    }

    .sidebar-nav-wrapper {
        display: none;
    }

    .header {
        display: none;
    }

    .footer {
        display: none;
    }

    .custom-btn {
        background-color: #FFB75A !important;
        border-color: #FFB75A !important;
        color: #071112 !important;
        font-weight: 600 !important;
    }


    .card-header {
        padding-top: 60px;
        padding-bottom: 40px;

    }

    .card-header img {
        display: block;
        margin: 18px auto;
    }

    .card-header h1,
    .card-header p {
        text-align: center;
        margin-top: 10px;
        color: #d1d5dc;
    }

    .forget-password {
        display: block;
        text-align: center;
        margin-top: 10px;

    }

    .full-height {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        background-color: #071112 !important;
    }

    .input-style-1 input {
        background: #d1d5dc !important;
        padding: 10px 16px !important;
    }

    .input-style-1 input::placeholder {
        font-size: 15px;
        font-weight: 600;
    }
</style>

@section('content')
    <div class="container full-height">
        <div class="row justify-content-center w-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <img class="mb-3 logo" width="120px" height="80px"
                            src="{{ asset($systemSetting->logo ?? 'frontend/contributions.png') }}" alt="logo" />
                        <h1>Welcome to Contributions</h1>
                        <p>Login and Start Admin Dashboard</p>
                    </div>
                    <div class="card-body">
                        @error('error')
                            <div class="alert alert-danger">
                                <strong>Note: </strong> {{ $message }}.
                            </div>
                        @enderror
                        @error('success')
                            <div class="alert alert-success">
                                <strong>Note: </strong> {{ $message }}.
                            </div>
                        @enderror
                        <form method="POST" action="{{ route('login') }}" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <div class="input-style-1">
                                    <input type="text" placeholder="Enter Email" id="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email" />
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-style-1">
                                    <input type="password" placeholder="Enter Password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password" />
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                            @endif
                            <br>
                            <button type="submit" class="btn btn-block btn-lg btn-primary form-control custom-btn">
                                {{ __('Login') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
