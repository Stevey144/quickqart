@php
$image = '/parent/assets/img/logo2.png';
@endphp
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>{{$store->name}}</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{$image}}">
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i">

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '963837190797100');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=963837190797100&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async
            src="https://www.googletagmanager.com/gtag/js?id=UA-175554990-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-175554990-1');
    </script>

    <script>
        window.shopDetails = @json($store);
    </script>


    <style class="site-preloader-style">
        @keyframes site-preloader-animation {
            from {
                transform: rotateZ(0deg);
            }
            to {
                transform: rotateZ(360deg);
            }
        }

        body {
            overflow: hidden !important;
            overflow-y: scroll !important;
            height: 100% !important;
        }

        .site-preloader {
            position: fixed;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            background-color: #fafafa;
            z-index: 99999;
            opacity: 1;
        }

        .site-preloader::before {
            box-sizing: border-box;
            content: '';
            display: block;
            position: absolute;
            left: calc(50% - 50px);
            top: calc(50% - 50px);
            width: 100px;
            height: 100px;
            border-radius: 50px;
            border: 3px solid rgba(0, 0, 0, .2);
            border-top-color: rgba(0, 0, 0, .6);
  0
            animation-name: site-preloader-animation;
            animation-duration: .5s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        .site-preloader__fade {
            transition: opacity .3s;
            opacity: 0;
        }
    </style>
<link rel="stylesheet" href="styles.0bb6d3182adf0be5c8f1.css"></head>
<body>
<div class="site-preloader"></div>
<app-root></app-root>
<script src="runtime-es2015.48d55d28e2cbf0319a66.js" type="module"></script>
<script src="runtime-es5.48d55d28e2cbf0319a66.js" nomodule defer></script>
<script src="polyfills-es5.f2c0771ebb464ad0bba6.js" nomodule defer></script>
<script src="polyfills-es2015.4265614f51b38bdc5abc.js" type="module"></script>
<script src="main-es2015.d3c92ad5afb9509e6152.js" type="module"></script>
<script src="main-es5.d3c92ad5afb9509e6152.js" nomodule defer></script>
</body>
</h
\ftml>

 0