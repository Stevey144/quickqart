@extends('auth.main2', ['bgImg' => 'password.jpg', 'bgSize' => '100%'])

@section('title')
    Reset Password
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
                                <span class="route-tab" href="">Reset Password</span>
                            </h6>
                            <div class="text-left mb-3" style="font-size: 80%">Please enter your phone number, reset code and your new password.</div>
                        </div>
                        <form class="account-form bg-white" method="POST" action="{{ route('password.update.code') }}">
                            @csrf
                            @include('parent.commons.alert')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                        <input class="is-prepended" type="text" required placeholder="Enter Account Phone Number"
                                               value="{{old('phone_number', session('phone_number'))}}" name="phone_number" id="sign-up">
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
                                                <i class="fa fa-user"></i>
                                            </span>
                                        <input class="is-prepended" type="text" required placeholder="Enter Password Reset Code"
                                               name="password_reset_code" id="sign-up">
                                        @if ($errors->has('password_reset_code'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('password_reset_code') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        <input class="is-prepended is-appended" type="password" placeholder="Enter Your Password" name="password" id="pass">

                                        <span class="form-append" onclick="toggleShowPassword()" id="show-password">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        @if ($errors->has('password'))
                                            <div id="password-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        <input class="is-prepended is-appended" type="password" placeholder="Enter Your Password Again" name="password_confirmation">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group d-flex justify-content-between">
                                <button type="submit" style="min-width: 100%; margin-top: 20px!important;">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
