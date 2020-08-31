<header>
    <div class="container fixed-top">
        <nav class="navbar navbar-expand-lg navbar-dark ">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="/assets/img/EVOQUE_Gold_Sign_256.png" alt="@lang('general.vtc_evoque')">
            </a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 text-uppercase font-weight-bold text-shadow">
                    <li class="nav-item @if(Request::is('/'))active @endif">
                        <a class="nav-link" href="#">@lang('navbar.about')</a>
                    </li>
                    <li class="nav-item @if(Request::is('/rules'))active @endif">
                        <a class="nav-link" href="#">@lang('navbar.rules')</a>
                    </li>
                    <li class="nav-item @if(Request::is('/apply'))active @endif">
                        <a class="nav-link" href="{{ route('apply') }}">@lang('navbar.apply')</a>
                    </li>
                    <li class="nav-item @if(Request::is('/login'))active @endif">
                        <a class="nav-link" href="#">@lang('navbar.login')<i class="fas fa-sign-in-alt"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
