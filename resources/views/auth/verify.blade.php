
@extends('auth.main')

@section('content')
    <div class="account-wrapper" style="max-width: 450px">
        <div class="account-body">
            <div class="account-header">
                <h4 class="title mb-20">Verify Your Email Address</h4>

                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif
                <div>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                </div>
            </div>
            <form class="account-form" method="POST" action="{{ route('verification.resend') }}">
                @csrf

                <div class="form-group text-center">
                    <button type="submit" style="min-width: 100%" class="mt-2 mb-2">{{ __('click here to request another') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
