@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>Donate</title>

    {{-- All Plugins CSS --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/nice-select.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/owl.carousel.min.css') }}" />

    {{-- Custom Css --}}
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/helper.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}" />

    {{-- FAVICON --}}
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ isset($systemSetting->favicon) && !empty($systemSetting->favicon) ? asset($systemSetting->favicon) : asset('frontend/icon.png') }}" />
</head>

<body>
    <main>
        <div class="container">
            <div class="main-wrapper d-flex justify-content-center">
                <div class="card-wrapper">
                    <div class="card-head">
                        <h3 class="card-title">Make a Contribution</h3>
                    </div>
                    <div class="card-main-body">
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

                        <div class="card-details">
                            <div class="collection-head">
                                <div class="row d-flex align-items-center">
                                    <div class="col-2">
                                        <img src="{{ asset($collection->image ?? 'frontend/assets/images/giftcard.png') }}"
                                            alt="">
                                    </div>
                                    <div class="col-10 collection-details">
                                        <h4>
                                            {{ $collection->name }}
                                        </h4>
                                        <p>
                                            Target Amount: ${{ number_format($collection->target_amount, 2) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            aria-valuenow="{{ $parsentage }}" aria-valuemin="0" aria-valuemax="100"
                                            style="width:{{ $parsentage }}%">
                                        </div>
                                    </div>
                                    <div class="progress-details">
                                        <p>Collected: <strong>${{ number_format($totalDonations, 2) }}</strong></p>
                                        <p>Deadline: <strong>
                                                {{ \Carbon\Carbon::parse($collection->deadline)->format('F d, Y') }}</strong>
                                        </p>
                                        <p>Participants: <strong>{{ $participants ?? '' }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="mt-5 alert alert-success text-center"
                                style="background-color: #ffbf6b !important; border: 0; font-size: 20px;">
                                Congratulations! {{ $payment->name }}, <br> You have successfully donated
                                ${{ number_format($payment->amount, 2) }}.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Javascript Links --}}
    <script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
</body>

</html>
