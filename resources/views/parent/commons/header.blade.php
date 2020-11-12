<header class="header-section header-white-dark">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo">
                <a href="/">
                    <img src="/parent/assets/img/logo.png" alt="logo">
                </a>
            </div>
            <ul class="menu">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="/#how-it-works">How It Works</a>
                </li>

                @guest
                    <li class="">
                        <a href="{{route('login')}}">Login</a>
                    </li>
                    <!--<li class="">-->
                    <!--    <a target="_blank" href="https://store.quickqart.com/login">Login to Store</a>-->
                    <!--</li>-->
                    <li class="">
                        <a href="{{route('register')}}" class="header-button"
                           style="min-width: 130px; text-align: center">Sign Up</a>
                    </li>
                @elseguest
                    <li class="">
                        <a href="/" class="header-button"
                           style="min-width: 130px; text-align: center">{{auth()->user()->name()}}</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{route('logout')}}">Logout</a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
            <div class="header-bar d-lg-none">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</header>
