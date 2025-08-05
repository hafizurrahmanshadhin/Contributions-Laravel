@extends('backend.app')

@section('title', 'Mail settings')

@section('content')
    {{--  ========== title-wrapper start ==========  --}}
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Mail Settings</h2>
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
                            <li class="active"><span><i class="lni lni-angle-double-right"></i></span>Mail Setting</li>
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
                    <form method="POST" action="{{ route('mail.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_mailer">MAIL MAILER:</label>
                                    <input type="text" placeholder="Enter mail mailer" id="mail_mailer"
                                        class="form-control @error('mail_mailer') is-invalid @enderror" name="mail_mailer"
                                        value="{{ env('MAIL_MAILER') }}" />
                                    @error('mail_mailer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_host">MAIL HOST:</label>
                                    <input type="text" placeholder="Enter mail host" id="mail_host"
                                        class="form-control @error('mail_host') is-invalid @enderror" name="mail_host"
                                        value="{{ env('MAIL_HOST') }}" />
                                    @error('mail_host')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_port">MAIL PORT:</label>
                                    <input type="text" placeholder="Enter mail port" id="mail_port"
                                        class="form-control @error('mail_port') is-invalid @enderror" name="mail_port"
                                        value="{{ env('MAIL_PORT') }}" />
                                    @error('mail_port')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_username">MAIL USERNAME:</label>
                                    <input type="text" placeholder="Enter mail username" id="mail_username"
                                        class="form-control @error('mail_username') is-invalid @enderror"
                                        name="mail_username" value="{{ env('MAIL_USERNAME') }}" />
                                    @error('mail_username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_password">MAIL PASSWORD:</label>
                                    <input type="text" placeholder="Enter mail password" id="mail_password"
                                        class="form-control @error('mail_password') is-invalid @enderror"
                                        name="mail_password" value="{{ env('MAIL_PASSWORD') }}" />
                                    @error('mail_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_encryption">MAIL ENCRYPTION:</label>
                                    <input type="text" placeholder="Enter mail encryption" id="mail_encryption"
                                        class="form-control @error('mail_encryption') is-invalid @enderror"
                                        name="mail_encryption" value="{{ env('MAIL_ENCRYPTION') }}" />
                                    @error('mail_encryption')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-style-1">
                                    <label for="mail_from_address">MAIL FROM ADDRESS:</label>
                                    <input type="text" placeholder="Enter mail from address" id="mail_from_address"
                                        class="form-control @error('mail_from_address') is-invalid @enderror"
                                        name="mail_from_address" value="{{ env('MAIL_FROM_ADDRESS') }}" />
                                    @error('mail_from_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
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
