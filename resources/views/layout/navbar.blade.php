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
                    @guest
                        <li class="nav-item @if(Route::current() && Route::current()->getName() === 'home')active @endif">
                            <a class="nav-link" href="{{ route('home') }}">О нас</a>
                        </li>
                        <li class="nav-item @if(Route::current() && Route::current()->getName() === 'convoys')active @endif">
                            <a class="nav-link" href="{{ route('convoys') }}">Конвой</a>
                        </li>
                        <li class="nav-item @if(Route::current() && Request::is('rules'))active @endif">
                            <a class="nav-link" href="{{ route('rules') }}">Правила</a>
                        </li>
                        <li class="nav-item @if(Route::current() && Request::is('apply'))active @endif">
                            <a class="nav-link" href="{{ route('apply') }}">Вступить</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.steam') }}">Войти <i class="fab fa-steam"></i></a>
                        </li>
                    @else
                        <li class="nav-item @if(Route::current()->getName() === 'home')active @endif">
                            <a class="nav-link" href="{{ route('home') }}">Главная</a>
                        </li>
                        <li class="nav-item dropdown @if(Route::current() && in_array(Route::current()->getName(), ['evoque.convoys.tab', 'evoque.convoys', 'convoys']))active @endif">
                            <a class="nav-link dropdown-toggle" href="#" id="convoysDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Конвои</a>
                            <div class="dropdown-menu" aria-labelledby="convoysDropdown">
                                <a class="dropdown-item" href="{{ route('convoys') }}">Регламенты</a>
                                <a class="dropdown-item" href="{{ route('convoys', 'public') }}">Открытый конвой</a>
                                @can('manage_convoys')
                                    <a class="dropdown-item" href="{{ route('evoque.convoys') }}">Редактирование</a>
                                @endcan
                            </div>
                        </li>
                        <li class="nav-item dropdown @if(Route::current() && Route::current()->getName() === 'evoque.rules')active @endif">
                            <a class="nav-link dropdown-toggle" href="#" id="rulesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Правила</a>
                            <div class="dropdown-menu" aria-labelledby="rulesDropdown">
                                <a class="dropdown-item" href="{{ route('evoque.rules', 'private') }}">Закрытые правила</a>
                                <a class="dropdown-item" href="{{ route('evoque.rules', 'public') }}">Публичные правила</a>
                            </div>
                        </li>
{{--                        <li class="nav-item @if(Route::current()->getName() === 'evoque.applications')active @endif">--}}
{{--                            <a class="nav-link" href="{{ route('evoque.applications') }}">Заявки</a>--}}
{{--                        </li>--}}
                        <li class="nav-item @if(Route::current() && Route::current()->getName() === 'evoque.members')active @endif">
                            <a class="nav-link" href="{{ route('evoque.members') }}">Таблица</a>
                        </li>
                        <li class="nav-item dropdown @if(Request::is('evoque/rp/*'))active @endif">
                            <a class="nav-link dropdown-toggle" href="#" id="rpDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Рейтинговые перевозки</a>
                            <div class="dropdown-menu" aria-labelledby="rpDropdown">
                                <a class="dropdown-item" href="{{ route('evoque.rp', 'ets2') }}">Статистика ETS2</a>
                                <a class="dropdown-item" href="{{ route('evoque.rp', 'ats') }}">Статистика ATS</a>
                                <a class="dropdown-item" href="{{ route('evoque.rp.reports') }}">Отчёты</a>
                            </div>
                        </li>
                        @can('admin')
                            <li class="nav-item dropdown @if(Request::is('evoque/admin/*'))active @endif">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Управление</a>
                                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <a class="dropdown-item" href="{{ route('evoque.admin.applications') }}">Заявки</a>
                                    <a class="dropdown-item" href="{{ route('evoque.admin.roles') }}">Роли</a>
                                    <a class="dropdown-item" href="{{ route('evoque.admin.users') }}">Пользователи</a>
                                </div>
                            </li>
                        @endcan
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle avatar p-0" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ Auth::user()->image }}" alt="Профиль">
                            </a>
                            <div class="dropdown-menu" aria-labelledby="profileDropdown">
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('evoque.profile') }}">Профиль</a>
                                    <a class="dropdown-item" href="{{ route('evoque.profile.edit') }}">Редактировать</a>
                                    <button type="submit" class="dropdown-item">Выйти <i class="fas fa-sign-out-alt"></i></button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>
