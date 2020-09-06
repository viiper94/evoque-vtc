<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="/assets/img/EVOQUE_Gold_Sign_256.png" alt="@lang('general.vtc_evoque')">
            </a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 text-uppercase font-weight-bold text-shadow">
                    <li class="nav-item @if(Request::is('/'))active @endif">
                        <a class="nav-link" href="{{ route('home') }}">@lang('navbar.about')</a>
                    </li>
                    <li class="nav-item @if(Request::is('convoys'))active @endif">
                        <a class="nav-link" href="{{ route('convoys') }}">@lang('navbar.convoys')</a>
                    </li>
                    <li class="nav-item @if(Request::is('rules'))active @endif">
                        <a class="nav-link" href="{{ route('rules') }}">@lang('navbar.rules')</a>
                    </li>
                    <li class="nav-item @if(Request::is('apply'))active @endif">
                        <a class="nav-link" href="{{ route('apply') }}">@lang('navbar.apply')</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.steam') }}">@lang('navbar.login') <i class="fab fa-steam"></i></a>
                        </li>
                    @else
                        <li class="nav-item @if(Request::is('evoque'))active @endif">
                            <a class="nav-link" href="{{ route('evoque') }}">@lang('navbar.login')</a>
                        </li>
                        <li class="nav-item @if(Request::is('profile'))active @endif">
                            <a class="nav-link" href="{{ route('profile') }}"><img src="{{ Auth::user()->image }}" alt="Профиль"></a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>
