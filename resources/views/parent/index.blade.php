@extends('parent.commons.layout')

@push('styles')
    <style>
        #screens-sections {
            padding-top: 50px!important;
            padding-bottom: 35px!important;
        }

        #screens-sections {
            position: relative;
        }

        #screens-sections .owl-nav .owl-next {
            right: -18px;
        }

        #screens-sections .owl-nav .owl-next:hover {
            right: -25px;
        }

        #screens-sections .owl-nav .owl-prev {
            left: -18px;
        }

        #screens-sections .owl-nav .owl-prev:hover {
            left: -25px;
        }

        #screens-sections .owl-nav [class*=owl-] {
            background: 0 0;
            font-size: 24px;
            text-transform: uppercase;
            position: absolute;
            top: 40%;
            line-height: 1;
            width: auto;
            opacity: 1;
            margin: 0;
            color: hsla(0, 0%, 100%, .3);
            transition: all .3s ease;
            font-weight: 400;
        }

        #screens-sections .owl-nav [class*=owl-] .fas {
            font-size: 35px!important;
            color: #c165dd;
        }

        @media screen and (max-width: 576px) {
            #screens-sections {
                padding-top: 10px!important;
                padding-bottom: 10px!important;
            }
            /*#screens-sections .owl-item {*/
            /*    width: 100%!important;*/
            /*}*/

            /*#screens-sections .owl-item img {*/
            /*    width: 100%;*/
            /*}*/
        }
    </style>
@endpush

@section('content')
    <section class="banner-8 oh">
        <div class="banner-shape-8 d-lg-block d-none">
            <img src="/parent/assets/img/market-your-products.png" alt="banner">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content-8 c-text-sm-center">
                        <h1 class="title c-text-sm-center">Your personal online store.</h1>
                        <p class=" c-text-sm-center">
                            Create an ecommerce website using our easy-to-use platform to display your products and manage your day-to-day sales activities.

                        </p>
                        <div class="banner-button-group c-text-sm-center">
                            <a href="/register" class="button-4">Sign Up for Free</a>
                            <a href="#how-it-works" class="button-4 active">Explore Features</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none">
                </div>
                <div class="col-12">
                    <div class="counter-wrapper-3 c-text-sm-center">
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="/parent/assets/img/icon/counter1.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="">29901+</span></h2>
                                <span class="name">Total Users</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="/parent/assets/img/icon/counter2.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="">53987+</span></h2>
                                <span class="name">Daily Visitors</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-thumb">
                                <img src="/parent/assets/img/icon/counter5.png" alt="icon">
                            </div>
                            <div class="counter-content">
                                <h2 class="title"><span class="">95</span><span>%</span></h2>
                                <span class="name">Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="work-section padding-bottom bg_img pb-md-0"
             data-background="/parent/assets/img/work/work-bg.jpg">
        <div class="oh padding-top pos-rel">
            <div class="top-shape d-none d-lg-block c-text-sm-center">
                <img src="/parent/assets/css/img/work-shape.png" alt="css">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-7">
                        <div class="section-header left-style cl-white  c-text-sm-center">
                            <h2 class="title  c-text-sm-center">How It Works</h2>
                            <p class=" c-text-sm-center">It's easier than you think. Follow the simple easy steps</p>
                        </div>
                    </div>
                </div>
                <div class="work-slider owl-carousel owl-theme" data-slider-id="2">
                    <div class="work-item">
                        <div class="work-thumb c-text-sm-center">
                            <img src="/parent/assets/img/reg.png" alt="work">
                        </div>
                        <div class="work-content cl-white c-text-sm-center">
                            <h3 class="title c-text-sm-center">Register</h3>
                            <p class="c-text-sm-center">Sign up with just an email and a password.</p>
                            <a href="/register" class="get-button white light">Get Started <i
                                    class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item c-text-sm-center">
                        <div class="work-thumb c-text-sm-center">
                            <img src="/parent/assets/img/populate-products.png" alt="work">
                        </div>
                        <div class="work-content cl-white c-text-sm-center">
                            <h3 class="title c-text-sm-center">Set Up</h3>
                            <p class=" c-text-sm-center">Enter your business name to create a store and start adding
                                your products. It takes less than 5 minutes.</p>
                            <a href="/register" class="get-button white light">Get Started <i
                                    class="flaticon-right"></i></a>
                        </div>
                    </div>
                    <div class="work-item c-text-sm-center">
                        <div class="work-thumb c-text-sm-center">
                            <img src="/parent/assets/img/yay-done.png" alt="work">
                        </div>
                        <div class="work-content cl-white c-text-sm-center">
                            <h3 class="title c-text-sm-center">Yay! Done</h3>
                            <p class="c-text-sm-center">Now your items are visible to the public and your customers can
                                start shopping. Simple right?!!
                                You are set to give your customers better shopping experience.</p>
                            <a href="/register" class="get-button white light">Get Started <i
                                    class="flaticon-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="screens-sections" class="bg-white">
        <div class="">
            <div class="container" style="max-width: 1100px">
                <div class="screens owl-carousel owl-theme">
                    <div style="width: 200px" class="item text-center">
                        <img src="/parent/assets/img/screens/1.png">
                    </div>
                    <div style="width: 200px" class="item text-center">
                        <img src="/parent/assets/img/screens/3.png">
                    </div>
                    <div style="width: 550px" class="item text-center">
                        <img src="/parent/assets/img/screens/2.png">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="access-section padding-bottom padding-top oh">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 rtl d-none d-lg-block c-text-sm-center">
                    <img src="/parent/assets/img/mobile-desktop.png" alt="quickqart store">
                </div>
                <div class="col-lg-6 c-text-sm-center">
                    <div class="access-content">
                        <div class="section-header left-style mb-olpo c-text-sm-center">
                            <h5 class="cate c-text-sm-center">Your business needs online presence</h5>
                            <h2 class="title c-text-sm-center">Build Your Brand Store</h2>
                            <p class="c-text-sm-center">Bring your products to life! Easily customizable platform make
                                all your products visible to your prospective buyers allowing them to checkout and
                                access their order with a single link.</p>
                        </div>
                        <a href="/register" class="get-button">get started for free <i class="flaticon-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="advance-feature-section padding-top-2 padding-bottom-2">
        <div class="container">
            <div class="advance-feature-item padding-top-2 padding-bottom-2 c-text-sm-center">
                <div class="advance-feature-thumb c-text-sm-center">
                    <img src="/parent/assets/img/store-domain2.png" alt="store-domain">
                </div>
                <div class="advance-feature-content c-text-sm-center">
                    <div class="section-header left-style mb-olpo c-text-sm-center">
                        <h5 class="cate">But my customers are offline…</h5>
                        <h2 class="title">Sell offline</h2>
                        <p>Now your customers can place an order before hand and come into your store to pay and pick
                            up.</p>
                    </div>
                    <a href="/" class="get-button">Get started now <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="advance-feature-item padding-top-2 padding-bottom-2">
                <div class="advance-feature-thumb c-text-sm-center">
                    <img src="/parent/assets/img/multi-language.png" alt="multilanguage">
                </div>
                <div class="advance-feature-content c-text-sm-center">
                    <div class="section-header left-style mb-olpo c-text-sm-center">
                        <h5 class="cate">Localisation</h5>
                        <h2 class="title">Multi-Currency Support</h2>
                        <p>Sell your products in whatever currency you want to.</p>
                    </div>
                    <a href="/register" class="get-button">start now for free <i class="flaticon-right"></i></a>
                </div>
            </div>
            <div class="advance-feature-item padding-top-2 padding-bottom-2 c-text-sm-center">
                <div class="advance-feature-thumb c-text-sm-center">
                    <img src="/parent/assets/img/full-featured-tools2.png" alt="feature">
                </div>
                <div class="advance-feature-content c-text-sm-center">
                    <div class="section-header left-style mb-olpo  c-text-sm-center">
                        <h5 class="cate">Simple tools and management.</h5>
                        <h2 class="title">Full-featured tools</h2>
                        <p>The simple, intuitive user interface and tools for product, order and customer management,
                            designed to help you create, execute, and analyze your sales.</p>
                    </div>
                    <a href="/register" class="get-button">sign up for free <i class="flaticon-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section padding-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq-header">
                        <div class="cate">
                            <img src="/parent/assets/img/cate.png" alt="cate">
                        </div>
                        <h2 class="title">Frequently Asked Questions</h2>
                        <a href="/faqs">More Questions <i class="flaticon-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-wrapper mb--38">
                        @foreach(App\Faq::query()->get() as $faq)
                            <div class="faq-item">
                                <div class="faq-thumb">
                                    <i class="flaticon-pdf"></i>
                                </div>
                                <div class="faq-content">
                                    <h4 class="title">{{$faq->question}}</h4>
                                    <p>
                                        {!! $faq->answer !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="trial-section padding-bottom padding-top">
        <div class="container">
            <div class="trial-wrapper padding-top padding-bottom pr  c-text-sm-center">
                <div class="ball-1">
                    <img src="/parent/assets/img/balls/balls.png" alt="">
                </div>
                <div class="trial-content cl-white c-text-sm-center">
                    <h3 class="title">Start your journey to making your products come to live today!</h3>

                    <div class="trial-button">
                        <a href="/register" class="transparent-button">Start Now <i class="flaticon-right ml-xl-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(function () {
            $('.screens').owlCarousel({
                margin: 100,
                loop: true,
                autoWidth: true,
                merge: true,
                nav: true,
                navText: [
                    '<span class="fas fa-chevron-left"></span>',
                    '<span class="fas fa-chevron-right"></span>'
                ],
                responsive: {
                    0: {
                        items: 1,
                        center: true,
                    },
                    600: {
                        items: 2,
                        nav: true
                    },
                    1000: {
                        items: 3,
                    }
                }
            })
        })
    </script>
@endpush
