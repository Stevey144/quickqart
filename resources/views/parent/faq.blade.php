@extends('parent.commons.layout')

@section('title')
    FAQs ::
@endsection

@section('content')
    <section class="page-header" style="background: -webkit-linear-gradient(0deg, #5c68f2 12%, #9468f9 67%, #cc68ff 100%);" data-background="/parent/assets/css/img/page-header.png">
        <div class="bottom-shape d-none d-md-block">
            <img src="/parent/assets/css/img/page-header.png" alt="css">
        </div>
        <div class="container">
            <div class="page-header-content cl-white">
                <h2 class="title">Questions & Answers</h2>
                <ul class="breadcrumb">
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        FAQs
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="faq-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-8">
                    <article class="mt-70 mt-lg-0">
                        <div class="faq--wrapper" id="get">
{{--                            <h3 class="main-title">Getting Started</h3>--}}
                            <div class="faq--area">
                                @foreach(App\Faq::query()->get() as $faq)
                                <div class="faq--item">
                                    <div class="faq-title">
                                        <h6 class="title">{{$faq->question}}</h6>
                                        <span class="icon"></span>
                                    </div>
                                    <div class="faq-content">
                                        <p>{!! $faq->answer !!}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="comunity-section padding-top padding-bottom oh pos-rel">
        <div class="comunity-bg bg_img" data-background="/parent/assets/img/faq-bg.jpg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-header cl-white">
                        <h5 class="cate">Still need help?</h5>
                        <h2 class="title">Stop Wasting Time</h2>
                        <p>Whether you’re stuck or just want some tips on where to start, any problem, hit up our experts anytime.</p>
                    </div>
                </div>
            </div>
            <div class="comunity-asking">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6 rtl d-none d-lg-block">
                        <img src="/parent/assets/img/help.png" alt="feature">
                    </div>
                    <div class="col-lg-6 col-xl-5 mb-30-none">
                        <div class="help-item">
                            <div class="help-thumb">
                                <img src="/parent/assets/img/icon/help2.png" alt="help">
                            </div>
                            <div class="help-content">
                                <h5 class="title">Contact us</h5>
                                <p>Send a mail to <a href="mailto:contact@quickqart.com">contact@quickqart.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
