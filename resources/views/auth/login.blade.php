@extends('auth.main2', ['bgImg' => 'login.png', 'bgSize' => '100%'])

@section('title')
    Login
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
                                <a class="route-tab active" href="{{route('login')}}">Login</a>
                                <a class="route-tab" href="{{route('register')}}">Register</a>
                            </h6>
                            <div class="text-left mb-3" style="font-size: 80%">Enter your credential to login</div>
                        </div>
                        <form class="account-form bg-white" method="POST" action="{{ route('login') }}">
                            @csrf
                            @include('parent.commons.alert')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-user"></i>
                                            </span>
                                        <input class="is-prepended" type="text" required
                                               placeholder="Enter Email Address or Phone Number"
                                               value="{{old('email')}}" name="email" id="sign-up">
                                        @if ($errors->has('email'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('email') }}</small>
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
                            </div>

                            <div class="form-group d-flex justify-content-between">
                                <div>
                                    <button type="submit" style="width: 150px; margin-top: 20px!important;">Sign In</button>
                                </div>
                                <a href="{{route('password.request')}}" style="margin-top: 30px!important; min-width: 150px">Recover password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
