@extends('parent.commons.layout')

@section('title')
    Contact Us ::
@endsection

@section('content')
    <section class="page-header single-header oh"
             style="background: -webkit-linear-gradient(0deg, #5c68f2 12%, #9468f9 67%, #cc68ff 100%);"
             data-background="/parent/assets/css/img/page-header.png">
        <div class="bottom-shape d-none d-md-block">
            <img src="/parent/assets/css/img/page-header.png" alt="css">
        </div>
    </section>
    <!--============= Contact Section Starts Here =============-->
    <section class="contact-section padding-top padding-bottom">
        <div class="container">
            <div class="section-header mw-100 cl-white">
                <h2 class="title">Contact Us</h2>
                <p>Whether you have a support question, a commercial query or a just feedback, get in touch.</p>
            </div>
            <div class="row justify-content-center justify-content-lg-between">
                <div class="col-lg-7">
                    @if ($errors->any())
                        <div class="alert m-alert alert-danger px-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert m-alert alert-danger">
                            {!! session('error') !!}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert m-alert alert-success">
                            {!! session('status') !!}
                        </div>
                    @endif
                    <div class="contact-wrapper">
                        <h4 class="title text-center mb-30">Get in Touch</h4>
                        <form action="{{route('contact')}}" class="contact-form" method="post" id="contact_form_submit">
                            @csrf
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" placeholder="Enter your full name" value="{{old('name')}}"
                                       name="name" id="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text" placeholder="Enter your email address" value="{{old('email')}}"
                                       name="email" id="email" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" placeholder="Enter your subject " value="{{old('subject')}}"
                                       name="subject" id="subject" required>
                            </div>
                            <div class="form-group mb-0">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" placeholder="Enter your message"
                                          required>{{old('message')}}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Send Message">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-content">
                        <div class="man d-lg-block d-none">
                            <img src="/parent/assets/img/contact/man.png" alt="bg">
                        </div>
                        <div class="contact-area">
                            <div class="contact-item">
                                <div class="contact-thumb">
                                    <img src="/parent/assets/img/contact/contact1.png" alt="contact">
                                </div>
                                <div class="contact-contact">
                                    <h5 class="subtitle">Email Us</h5>
                                    <a href="Mailto:info@mosto.com">contact@quickqart.com</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-thumb">
                                    <img src="/parent/assets/img/contact/contact2.png" alt="contact">
                                </div>
                                <div class="contact-contact">
                                    <h5 class="subtitle">We're Hiring</h5>
                                    <a href="Tel:565656855">Send CV to contact@quickqart.com</a>
                                    <a href="Tel:565656855"></a>
                                </div>
                            </div>
                            {{--                            <div class="contact-item">--}}
                            {{--                                <div class="contact-thumb">--}}
                            {{--                                    <img src="/parent/assets/img/contact/contact3.png" alt="contact">--}}
                            {{--                                </div>--}}
                            {{--                                <div class="contact-contact">--}}
                            {{--                                    <h5 class="subtitle">Visit Us</h5>--}}
                            {{--                                    <p>4293 Euclid Avenue, Los Angeles,CA 90012</p>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============= Contact Section Ends Here =============-->


    <!--============= Map Section Starts Here =============-->
    {{--    <div class="map-section padding-bottom-2">--}}
    {{--        <div class="container">--}}
    {{--            <div class="section-header">--}}
    {{--                <div class="thumb">--}}
    {{--                    <img src="/parent/assets/img/contact/earth.png" alt="contact">--}}
    {{--                </div>--}}
    {{--                <h3 class="title">We Are Easy To Find</h3>--}}
    {{--            </div>--}}
    {{--            <div class="row justify-content-center">--}}
    {{--                <div class="col-md-10">--}}
    {{--                    <div class="maps-wrapper">--}}
    {{--                        <div class="maps"></div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <!--============= Map Section Ends Here =============-->
@endsection
