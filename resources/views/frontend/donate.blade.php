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
                                    <div class="col-lg-2 col-md-6">
                                        <img src="{{ asset($collection->image ?? 'frontend/assets/images/giftcard.png') }}"
                                            alt="">
                                    </div>
                                    <div class="col-lg-10 col-md-6 collection-details">
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
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"
                                            style="width:{{ $percentage }}%">
                                        </div>
                                    </div>
                                    <div class="progress-details">
                                        <p>Collected: <strong>${{ number_format($totalDonations, 2) }}</strong></p>
                                        <p>Deadline:
                                            <strong>{{ \Carbon\Carbon::parse($collection->deadline)->format('F d, Y') }}</strong>
                                        </p>
                                        <p>Participants: <strong>{{ $participants ?? '' }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Check if the collection deadline has passed --}}
                        @if (isset($message))
                            {{-- Stylish and user-friendly message --}}
                            <div class="alert alert-warning text-center mt-4 p-4"
                                style="background-color: #ffebcc; border-color: #ffcc80;">
                                <h4 class="text-danger mb-2"><i class="fas fa-exclamation-triangle"></i> Donation Period
                                    Ended</h4>
                                <p class="mb-0">Unfortunately, the donation period for
                                    <strong>{{ $collection->name }}</strong> has ended. Thank you for your interest and
                                    support!
                                </p>
                            </div>
                        @else
                            {{-- Display the donation form if the collection is still active --}}
                            <form action="{{ route('checkout') }}" class="payment-gateway-form" method="POST">
                                @csrf
                                <div class="card-form">
                                    <div class="form-group row mb-2 d-flex align-items-center">
                                        <div class="col-6">
                                            <label for="name">Enter Your Name</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="name" id="name" placeholder="Name"
                                                class="form-control">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row d-flex align-items-center">
                                        <div class="col-6">
                                            <label for="amount">Enter Your Amount</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="amount" id="amount" placeholder="$"
                                                class="form-control">
                                            @error('amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Gateway --}}
                                <div class="card-form">
                                    <h4 class="payment-gateway-title">
                                        Payment Gateway
                                    </h4>
                                    <input type="hidden" name="collection_id" value="{{ $collection->id }}">
                                    <div
                                        class="payment-item form-group form-check-inline row mb-2 d-flex align-items-center">
                                        <div class="col-lg-11 col-sm-11">
                                            <img src="{{ asset('frontend/assets/images/stripe.png') }}" alt="">
                                            <label class="form-check-label" for="paymentMethod">Stripe</label>
                                        </div>
                                        <div class="col-lg-1 col-sm-1">
                                            <input class="form-check-input" checked type="radio" name="paymentMethod"
                                                id="paymentMethod" value="stripe">
                                        </div>
                                    </div>
                                    @error('paymentMethod')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Card-footer --}}
                                <div class="card-footer row mt-5">
                                    <div class="col-6 pe-3">
                                        <button class="btn btn-outline-warning form-control">Cancel</button>
                                    </div>
                                    <div class="col-6 ps-3">
                                        <button type="submit" class="btn btn-primary form-control">Contribute</button>
                                    </div>
                                </div>
                            </form>
                        @endif
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
