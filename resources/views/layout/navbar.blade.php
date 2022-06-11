<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-gradient">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="/assets/img/EVOQUE_Gold_Sign_256.png" alt="@lang('general.vtc_evoque')">
            </a>
            <div class="collapse navbar-collapse pb-5 pb-lg-0 mb-5 mb-lg-0" id="navbarTogglerDemo01">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 pb-5 pb-lg-0 mb-5 mb-lg-0 text-uppercase font-weight-bold text-shadow">
                    @if(\Illuminate\Support\Facades\Auth::guest() || !\Illuminate\Support\Facades\Auth::user()->member)
                        <li @class(['nav-item', 'active' => Route::is('home')])>
                            <a class="nav-link" href="{{ route('home') }}">О нас</a>
                        </li>
                        <li @class(['nav-item', 'active' => Route::is('convoy.public')])>
                            <a class="nav-link" href="{{ route('convoy.public') }}">Конвой</a>
                        </li>
                        <li @class(['nav-item', 'active' => Route::is('members')])>
                            <a class="nav-link" href="{{ route('members') }}">Сотрудники</a>
                        </li>
                        <li @class(['nav-item', 'active' => Route::is('rules')])>
                            <a class="nav-link" href="{{ route('rules') }}">Правила</a>
                        </li>
                        <li @class(['nav-item', 'active' => Route::is('apply')])>
                            <a class="nav-link" href="{{ route('apply') }}">Вступить</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('auth.steam') }}">Войти <i class="fab fa-steam"></i></a>
                        </li>
                    @else
                        <li @class(['nav-item dropdown', 'active' => in_array($controller, ['tabscontroller', 'convoyscontroller', 'planscontroller'])])>
                            <a class="nav-link dropdown-toggle nowrap" id="convoysDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Конвои
                                @if($convoys_c > 0)
                                    <span class="badge badge-danger">{{ $convoys_c }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="convoysDropdown">
                                <a class="dropdown-item" href="{{ route('convoys.private') }}">
                                    Регламенты
                                    @if($bookings_c > 0)
                                        <span class="badge badge-danger">{{ $bookings_c }}</span>
                                    @endif
                                </a>
                                @can('update', \App\Convoy::class)
                                    <a class="dropdown-item" href="{{ route('convoy.public') }}">Открытый конвой</a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('evoque.convoys.plans') }}">Планы по конвоям</a>
                                <a class="dropdown-item nowrap" href="{{ route('evoque.convoys.tab') }}">
                                    Скрины TAB
                                    @can('accept', \App\Tab::class)
                                        @if($tabs_c > 0)
                                            <span class="badge badge-danger">{{ $tabs_c }}</span>
                                        @endif
                                    @endcan
                                </a>
                            </div>
                        </li>
                        <li @class(['nav-item dropdown', 'active' => in_array($controller, ['rulescontroller'])])>
                            @can('update', \App\Rules::class)
                                <a class="nav-link dropdown-toggle" id="rulesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Правила</a>
                                <div class="dropdown-menu" aria-labelledby="rulesDropdown">
                                    <a class="dropdown-item" href="{{ route('evoque.rules', 'private') }}">Закрытые правила</a>
                                    <a class="dropdown-item" href="{{ route('evoque.rules', 'public') }}">Публичные правила</a>
                                </div>
                            @else
                                <a class="nav-link" href="{{ route('evoque.rules', 'private') }}">Правила</a>
                            @endcan
                        </li>
                        <li @class(['nav-item', 'active' => in_array($controller, ['applicationscontroller', 'recruitmentcontroller'])])>
                            <a class="nav-link nowrap" href="{{ route('evoque.applications') }}">
                                Заявки
                                @if($applications_badge > 0)
                                    <span class="badge badge-danger">{{ $applications_badge }}</span>
                                @endif
                            </a>
                        </li>
                        <li @class(['nav-item', 'active' => $controller === 'memberscontroller'])>
                            <a class="nav-link" href="{{ route('evoque.members') }}">Таблица</a>
                        </li>
                        <li @class(['nav-item dropdown', 'active' => Request::is('evoque/rp*')])>
                            <a class="nav-link dropdown-toggle nowrap" id="rpDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Рейтинговые перевозки
                                @can('claim', \App\RpReport::class)
                                    @if($reports_c > 0)
                                        <span class="badge badge-danger">{{ $reports_c }}</span>
                                    @endif
                                @endcan
                            </a>
                            <div class="dropdown-menu" aria-labelledby="rpDropdown">
                                <a class="dropdown-item" href="{{ route('evoque.rp') }}">Статистика</a>
                                <a class="dropdown-item" href="{{ route('evoque.rp.weekly') }}">Итоги за неделю</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('evoque.rp.reports') }}">
                                    Отчёты
                                    @can('claim', \App\RpReport::class)
                                        @if($reports_c > 0)
                                            <span class="badge badge-danger">{{ $reports_c }}</span>
                                        @endif
                                    @endcan
                                </a>
                                @can('create', \App\RpReport::class)
                                    <a class="dropdown-item" href="{{ route('evoque.rp.reports.add') }}">Подать отчёт</a>
                                @endcan
                            </div>
                        </li>
                        @if(\Illuminate\Support\Facades\Auth::user()->can('view', \App\Role::class) || \Illuminate\Support\Facades\Auth::user()->can('view', \App\User::class))
                            <li @class(['nav-item dropdown', 'active' => Request::is('evoque/admin*')])>
                                <a class="nav-link dropdown-toggle" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Управление</a>
                                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                                    @can('view', \App\Role::class)
                                        <a class="dropdown-item" href="{{ route('evoque.admin.roles') }}">Должности</a>
                                    @endcan
                                    @can('view', \App\User::class)
                                        <a class="dropdown-item" href="{{ route('evoque.admin.users') }}">Пользователи</a>
                                    @endcan
                                    @can('restore', \App\Member::class)
                                        <a class="dropdown-item" href="{{ route('evoque.members.trash') }}">Уволенные сотрудники</a>
                                    @endcan
                                    <a class="dropdown-item" href="{{ route('evoque.tuning') }}">Официальный тюнинг</a>
                                </div>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle avatar p-0 d-flex align-items-center" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->member->nickname }}">
                                <span class="ml-1 d-lg-none">{{ Auth::user()->member->nickname }}</span>
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
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
