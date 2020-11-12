<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')QuickQart</title>

    <link rel="stylesheet" href="/parent/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/parent/assets/css/all.min.css">
    <link rel="stylesheet" href="/parent/assets/css/animate.css">
    <link rel="stylesheet" href="/parent/assets/css/nice-select.css">
    <link rel="stylesheet" href="/parent/assets/css/owl.min.css">
    <link rel="stylesheet" href="/parent/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/parent/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="/parent/assets/css/flaticon.css">
    <link rel="stylesheet" href="/parent/assets/css/main.css">

    <link rel="shortcut icon" href="/parent/assets/img/logo2.png" type="image/x-icon">
    
        
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
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=963837190797100&ev=PageView
&noscript=1"/>
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
    <style>
        .m-alert {
            font-size: 23px;
            border-bottom-left-radius: 0;
            border-top-left-radius: 0;
            border-left-width: 5px;
            padding: 10px 15px;
        }

        .m-alert.alert-danger {
            border-left-color: #7A4A46;
        }

        .m-alert.alert-success {
            border-left-color: darkgreen;
        }
    </style>

    @stack('styles')
</head>

<body>
<!--============= ScrollToTop Section Starts Here =============-->
<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<a href="#0" class="scrollToTop"><i class="fas fa-angle-up"></i></a>
<div class="overlay"></div>

@include('parent.commons.header')
<!--============= Header Section Ends Here =============-->


@yield('content')

<!--============= Footer Section Starts Here =============-->
@include('parent.commons.footer')

<script src="/parent/assets/js/jquery-3.3.1.min.js"></script>
<script src="/parent/assets/js/modernizr-3.6.0.min.js"></script>
<script src="/parent/assets/js/plugins.js"></script>
<script src="/parent/assets/js/bootstrap.min.js"></script>
<script src="/parent/assets/js/magnific-popup.min.js"></script>
<script src="/parent/assets/js/jquery-ui.min.js"></script>
<script src="/parent/assets/js/wow.min.js"></script>
<script src="/parent/assets/js/waypoints.js"></script>
{{--    <script src="/parent/assets/js/nice-select.js"></script>--}}
<script src="/parent/assets/js/owl.min.js"></script>
<script src="/parent/assets/js/counterup.min.js"></script>
<script src="/parent/assets/js/paroller.js"></script>
<script src="/parent/assets/js/main.js"></script>

@stack('js')
</body>

</html>
