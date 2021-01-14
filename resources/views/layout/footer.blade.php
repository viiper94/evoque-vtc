<footer>
    <div class="container py-5">
        <div class="row justify-content-between">
            <div class="col-md-auto col-sm-12 row flex-column justify-content-center align-items-center ml-0">
                <h2>Наши контакты</h2>
                <div class="contacts row mx-0 justify-content-between flex-nowrap w-100">
                    <a href="https://vk.com/evoquevtc" target="_blank" class=""><i class="fab fa-vk"></i></a>
                    <a href="https://discord.gg/Gj53a8d" target="_blank" class=""><i class="fab fa-discord"></i></a>
                    <a href="http://invite.teamspeak.com/evoque.ts3srv.ru" target="_blank" class=""><i class="fab fa-teamspeak"></i></a>
                    <a href="https://truckersmp.com/vtc/11682" target="_blank" class=""><i class="fas fa-truck-pickup"></i></a>
                    <a href="https://trucksbook.eu/company/11661" target="_blank" class=""><i class="fontello icon-trucksbook"></i></a>
                </div>
            </div>
            <div class="col-md-auto col-sm-12">
                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apply') }}">Вступить</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery') }}">Галлерея
                            @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->can('toggle', \App\Gallery::class))
                                <span class="badge badge-danger">{{ $gallery_c }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kb') }}">База знаний</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('privacy') }}">Политика конфиденциальности</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-auto col-sm-12">
                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('members') }}">Команда</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://truckersmp.com/reports/create" target="_blank">Жалоба на водителя</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('convoy.public') }}">Открытый конвой</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('terms') }}">Правила пользования</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-auto col-sm-12 row flex-column justify-content-center align-items-center ml-0">
                <h2>@lang('general.vtc_evoque')</h2>
                <span class="copyright">Все права защищены &copy; {{ date('Y') }}</span>
            </div>
        </div>
    </div>
</footer>
