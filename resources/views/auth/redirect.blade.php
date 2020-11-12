@extends('auth.main')

@section('title')
    Login Successful - You will get redirected
@endsection

@section('content')
    <div class="account-wrapper" style="max-width: 700px; padding-bottom: 10px!important;">
        <div class="account-body pb-0" style="padding-bottom: 10px!important;">
            <div class="account-header">
                <h4 class="title mt-20">Logged In Successful</h4>
                <h4 class="title mt-20" style="font-size: 20px!important;">
                    You will get redirected automatically. Use the Button below if this take longer than a minute.
                </h4>
                <div class="account-form text-center">
                    <div class="form-group">
                        <a target="_blank" href="{{$redirectRoute}}" class="m-btn-sm-block" style="display: inline-block">Redirect now</a>
                    </div>
                </div>

                <div style="height: 30px"></div>
            </div>
        </div>
    </div>
{{--    <script>--}}
{{--        location.href = "{{$redirectRoute}}";--}}
{{--    </script>--}}
@endsection
