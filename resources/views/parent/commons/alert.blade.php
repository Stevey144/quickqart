@if (session('status'))
    <div class="alert alert-success" role="alert">
        {!! session()->get('status') !!}
    </div>
@endif
@if (session('verified'))
    <div class="alert alert-success" role="alert">
        {!! session()->get('verified') !!}
    </div>
@endif

@if (session('require.verification'))
    <form style="display: none!important;" class="verify-form" method="POST"
          action="{{ route('verification.resend') }}">
        @csrf
        <input type="hidden" name="id" value="{{session()->get('user')}}">
    </form>
    <div class="alert alert-danger text-left" role="alert">
        {!! session()->get('require.verification') !!}
    </div>
@endif



@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {!! session()->get('error') !!}
    </div>
@endif
