@extends('parent.commons.layout')

@section('title')
    Page not found ::
@endsection

@section('content')
    <div class="error-section bg_img" data-background="/parent/assets/img/404/404-bg.jpg">
        <div class="container">
            <div class="man1">
                <img src="/parent/assets/img/404/man_01.png" alt="404" class="wow bounceInUp" data-wow-duration=".5s" data-wow-delay=".5s">
            </div>
            <div class="man2">
                <img src="/parent/assets/img/404/man_02.png" alt="404" class="wow bounceInUp" data-wow-duration=".5s">
            </div>
            <div class="error-wrapper wow bounceInDown" data-wow-duration=".7s" data-wow-delay="1s">
                <h1 class="title">404</h1>
                <h3 class="subtitle">Page Not Found</h3>
                <a href="/" class="button-5">Go Back</a>
            </div>
        </div>
    </div>
@endsection
