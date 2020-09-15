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
                        <a class="nav-link" href="{{ route('home') }}">@lang('navbar.evoque.home')</a>
                    </li>
                    <li class="nav-item @if(Request::is('evoque.convoys'))active @endif">
                        <a class="nav-link" href="{{ route('evoque.convoys') }}">@lang('navbar.evoque.convoys')</a>
                    </li>
                    <li class="nav-item @if(Request::is('evoque.applications'))active @endif">
                        <a class="nav-link" href="{{ route('evoque.applications') }}">@lang('navbar.evoque.applications')</a>
                    </li>
                    <li class="nav-item @if(Request::is('evoque.rules'))active @endif">
                        <a class="nav-link" href="{{ route('evoque.rules') }}">@lang('navbar.rules')</a>
                    </li>
                    <li class="nav-item @if(Request::is('evoque.table'))active @endif">
                        <a class="nav-link" href="{{ route('evoque.table') }}">@lang('navbar.evoque.table')</a>
                    </li>
                    <li class="nav-item @if(Request::is('evoque.rp'))active @endif">
                        <a class="nav-link" href="{{ route('evoque.rp') }}">@lang('navbar.evoque.rp')</a>
                    </li>
                    @can('admin')
                        <li class="nav-item @if(Request::is('evoque.admin'))active @endif">
                            <a class="nav-link" href="{{ route('evoque.admin') }}">@lang('navbar.evoque.admin')</a>
                        </li>
                    @endcan
                    <li class="nav-item @if(Request::is('profile'))active @endif">
                        <a class="nav-link avatar p-0" href="{{ route('profile') }}"><img src="{{ Auth::user()->image }}" alt="Профиль"></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
