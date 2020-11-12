@extends('auth.main2', ['bgImg' => 'login.png'])

@section('title')
    Confirm Password
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
                                <span class="route-tab" href="">Confirm Password</span>
                            </h6>
                            <div class="text-left mb-3" style="font-size: 80%">Please confirm your password before continuing.</div>
                        </div>
                        <form class="account-form bg-white" method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            @include('parent.commons.alert')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                            <span class="form-prepend">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                        <input class="is-prepended" type="password" placeholder="Enter Your Password" name="password" id="pass">
                                        @if ($errors->has('password'))
                                            <div id="email-error" class="error text-danger"
                                                 style="display: block;">
                                                <small>{{ $errors->first('password') }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-between">
                                <button type="submit" style="width: 100%; margin-top: 20px!important;">Confirm Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
