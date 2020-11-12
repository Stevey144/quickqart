@extends('auth.main')
@section('title')
    Registration Completed
@endsection

@section('title')
    Registration Completed
@endsection

@section('content')
    <div class="account-wrapper" style="max-width: 700px; padding-bottom: 10px!important;">
        <div class="account-body pb-0" style="padding-bottom: 10px!important;">
            <div class="account-header">
                <h4 class="title mt-20">Registration successful</h4>
                <h4 class="title mt-20" style="font-size: 20px!important;">
                    {!! session()->get('reg_completed') !!}
                </h4>
                <form class="account-form text-center" method="POST" action="{{ route('verification.resend') }}">
                    <div class="form-group">
                        @csrf
                        <input type="hidden" name="id" value="{{session()->get('user')}}">

                        <button class="m-btn-sm-block" style="display: inline-block">Resend activation link</button>
                    </div>
                </form>

                <div style="height: 30px"></div>
            </div>
        </div>
    </div>
@endsection
