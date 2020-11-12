@extends('auth.main2', ['bgImg' => 'forgot_password.png'])

@section('title')
    Activate Account
@endsection

@section('title')
    Activate Account
@endsection


@section('content')
    <div class="col-md-5 bg-white vh-100">
        <div class="row d-flex justify-content-center">
            <div class="col-md-9">
                <div class="account-wrapper vh-100">
                    <div class="account-body">
                        <div class="account-header">
                            <div class="text-left mb-5 d-block d-md-none">
                                <a href="/"><img style="height: 60px" src="/parent/assets/img/logo.png"></a>
                            </div>
                            <h6 class="title mb-3 text-left">
                                <span class="route-tab" href="">Activate Account</span>
                            </h6>
                            <div class="text-left mb-3" style="font-size: 80%">Please enter the code sent to you via sms to activate your account</div>
                        </div>
                        <form class="account-form bg-white" method="POST" action="{{ route('activate.token') }}">
                            @csrf
                            @include('parent.commons.alert')
                            @php
                            $phone = session()->get('phone_number');
                            @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                        <input class="is-prepended" type="tel" required placeholder="Enter Phone Number"
                                               value="{{old('phone_number', $phone ? $phone : '')}}" name="phone_number"
                                               id="phone-field">
                                        @if ($errors->has('phone_number'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('phone_number') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        <input class="is-prepended" type="text" required
                                               placeholder="Enter Activation Code"
                                               name="activation_code" id="activation_code">
                                        @if ($errors->has('activation_code'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('activation_code') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-between">
                                <button type="submit" style="width: 100%; margin-top: 20px!important;">
                                    Activate Account
                                </button>
                            </div>
                        </form>
                        <div class="mt-1 font-weight-bold" style="font-size: 80%">If you don't receive an SMS after about 5 minutes,
                            <a id="activation-link" href="/activate/token/{phone}">
                                Click here to resend a new activation code</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $('#activation-link').click(function (event) {
                event.preventDefault();
                let phone = $('#phone-field').val().trim();

                if (phone) {
                    location.href = $(this).attr('href').toString().replace("{phone}", phone);
                }
            });
        });
    </script>
@endpush
