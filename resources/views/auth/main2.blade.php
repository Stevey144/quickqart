<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title') QuickQart</title>

    <link rel="stylesheet" href="/parent/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/parent/assets/css/all.min.css">
    <link rel="stylesheet" href="/parent/assets/css/animate.css">
    <link rel="stylesheet" href="/parent/assets/css/nice-select.css">
    <link rel="stylesheet" href="/parent/assets/css/owl.min.css">
    <link rel="stylesheet" href="/parent/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/parent/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="/parent/assets/css/flaticon.css">
    <link rel="stylesheet" href="{{mix('/parent/assets/css/main.css')}}">

    
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
    <link rel="shortcut icon" href="/parent/assets/img/logo2.png" type="image/x-icon">

    <style>
        .route-tab {
            font-size: 24px;
            color: #0a3d62!important;
            margin-right: 20px;
            padding-bottom: 5px!important;
        }

        .route-tab.active {
            border-bottom: 3px solid #0a3d62;
        }
        .form-group, .alert {
            width: 100%;
            position: relative;
            margin-bottom: 35px!important;
        }

        .form-append, .form-prepend {
            position: absolute;
        }

        .form-append {
            right: 10px !important;
            top: 8px;
        }

        .form-group .is-prepended {
            padding-left: 40px!important;
        }

        .form-group .is-appended {
            padding-right: 40px!important;
        }

        .form-prepend {
            left: 10px !important;
            top: 8px;
        }



        .form-group input {
            border-width: 0 0 2px 0!important;
            border-color: #ccc!important;
            border-radius: 0!important;
        }

        .account-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .error span {
            line-height: 1.2;
        }

        .alert {
            line-height: 1.5!important;
            padding: 10px 15px;
            border-width: 0 0 0 5px!important;
            border-radius: 0!important;
        }

        .alert-danger {
            border-color: #721c24;
        }

        .alert-success {
            border-color: #155724;
        }
    </style>
    <script>
        function toggleShowPassword() {
            const toggleBtn = $('#show-password');
            if (toggleBtn) {
                const field = toggleBtn.closest('.form-group').find('input').first();

                if (field) {
                    let type = 'password', icon1 = 'fa-eye',
                        icon2 = 'fa-eye-slash';
                    if (field.attr('type') === 'password') {
                        type = 'text';
                        icon1 = 'fa-eye-slash';
                        icon2 = 'fa-eye';
                    }

                    field.attr('type', type);
                    toggleBtn.find('.fa').addClass(icon1).removeClass(icon2);
                }
            }
        }
    </script>
    @yield('style')
</head>

<body>

<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>

@php
if (!isset($bgImg)) {
    $bgImg = 'full-featured-tools.svg';
}

if (!isset($bgSize)) {
    $bgSize = '50%';
}
@endphp
<div class="">
    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-md-7 vh-100 d-none d-md-flex bg_img" style="background-size: {{$bgSize}}!important;" data-background="/parent/assets/img/{{$bgImg}}">
                <div style="position: absolute; top: 30px; left: 30px">
                    <a href="/"><img style="height: 50px" src="/parent/assets/img/logo.png"></a>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
</div>

<script src="/parent/assets/js/jquery-3.3.1.min.js"></script>
<script src="/parent/assets/js/modernizr-3.6.0.min.js"></script>
<script src="/parent/assets/js/plugins.js"></script>
<script src="/parent/assets/js/bootstrap.min.js"></script>
{{--<script src="/parent/assets/js/magnific-popup.min.js"></script>--}}
<script src="/parent/assets/js/jquery-ui.min.js"></script>
{{--<script src="/parent/assets/js/wow.min.js"></script>--}}
{{--<script src="/parent/assets/js/waypoints.js"></script>--}}
{{--<script src="/parent/assets/js/nice-select.js"></script>--}}
{{--<script src="/parent/assets/js/owl.min.js"></script>--}}
{{--<script src="/parent/assets/js/counterup.min.js"></script>--}}
<script src="/parent/assets/js/paroller.js"></script>
{{--<script src="/parent/assets/js/countdown.js"></script>--}}
<script src="/parent/assets/js/main.js"></script>

<script>
    $(document.body).on('click', '.resend-btn', function (e) {
        alert('hello');
        e.preventDefault();
        $('.verify-form').first().submit();
    });
</script>

@stack('js')


</body>

</html>
