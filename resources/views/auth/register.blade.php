@extends('auth.main2', ['bgImg' => 'login.png', 'bgSize' => '100%'])
@section('title')
    Registration
@endsection


@section('content')
    <div class="col-md-5 bg-white vh-100">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="account-wrapper vh-100">
                    <div class="account-body">
                        <div class="account-header">
                            <div class="text-left mb-5 d-block d-md-none">
                                <a href="/"><img style="height: 60px" src="/parent/assets/img/logo.png"></a>
                            </div>
                            <h6 class="title mb-3 text-left">
                                <a class="route-tab" href="{{route('login')}}">Login</a>
                                <a class="route-tab active" href="{{route('register')}}">Register</a>
                            </h6>
                            <div class="text-left mb-3" style="font-size: 80%">Enter your information to setup an account</div>
                        </div>
                        <form class="account-form bg-white" method="POST" action="{{ route('register') }}">
                            @csrf
                            @include('parent.commons.alert')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-user"></i>
                                            </span>
                                        <input class="is-prepended" type="text" required placeholder="Enter name (last name first) "
                                               value="{{old('name')}}" name="name" id="sign-up">
                                        @if ($errors->has('name'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('name') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                        <input class="is-prepended" type="text" placeholder="Email Address Or Phone Number"
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

                            <div class="form-group text-center">
                                <button type="submit" style="min-width: 100%" class="mt-3 mb-3">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


