@extends('backend.app')

@section('title', 'google settings')

@section('content')
    {{--  ========== title-wrapper start ==========  --}}
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Google Settings</h2>
                </div>
            </div>

            <div class="col-md-6">
                <div class="breadcrumb-wrapper">
                    <nav>
                        <ol class="base-breadcrumb breadcrumb-three">
                            <li>
                                <a href="{{ route('home') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 0a8 8 0 1 0 4.596 14.104A5.934 5.934 0 0 1 8 13a5.934 5.934 0 0 1-4.596-2.104A7.98 7.98 0 0 0 8 0zm-2 3a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm-1.465 5.682A3.976 3.976 0 0 0 4 9c0 1.044.324 2.01.882 2.818a6 6 0 1 1 6.236 0A3.975 3.975 0 0 0 12 9a3.976 3.976 0 0 0-.536-1.318l-1.898.633-.018-.056 2.194-.732a4 4 0 1 0-7.6 0l2.194.733-.018.056-1.898-.634z" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li><span><i class="lni lni-angle-double-right"></i></span>Settings</li>
                            <li class="active"><span><i class="lni lni-angle-double-right"></i></span>Google Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{--  ========== title-wrapper end ==========  --}}

    <div class="form-layout-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-4">
                    <form method="POST" action="{{ route("google.update") }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="google_client">GOOGLE_CLIENT_ID:</label>
                                    <input type="text" placeholder="Enter google client Id" id="google_client"
                                        class="form-control @error('google_client') is-invalid @enderror" name="google_client"
                                        value="{{ env('GOOGLE_CLIENT_ID') }}" />
                                    @error('google_client')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="google_client_secret">GOOGLE_CLIENT_SECRET:</label>
                                    <input type="text" placeholder="Enter mail host" id="google_client_secret"
                                        class="form-control @error('google_client_secret') is-invalid @enderror" name="google_client_secret"
                                        value="{{ env('GOOGLE_CLIENT_SECRET') }}" />
                                    @error('google_client_secret')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="google_redirect_uri">GOOGLE_REDIRECT_URI:</label>
                                    <input type="link" placeholder="Enter mail port" id="google_redirect_uri"
                                        class="form-control @error('google_redirect_uri') is-invalid @enderror" name="google_redirect_uri"
                                        value="{{ env('GOOGLE_REDIRECT_URI') }}" />
                                    @error('google_redirect_uri')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>



                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('home') }}" class="btn btn-danger me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
