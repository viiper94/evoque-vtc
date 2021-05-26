<footer>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-auto col-md-6 col-xl-auto mb-md-0 contacts">
                <h2>Наши контакты</h2>
                <div class="contacts-links mx-0">
                    <a href="https://vk.com/evoquevtc" target="_blank" class=""><i class="fab fa-vk"></i></a>
                    <a href="https://discord.gg/Gj53a8d" target="_blank" class=""><i class="fab fa-discord"></i></a>
                    <a href="ts3server://evoque.ts3srv.ru" target="_blank" class=""><i class="fab fa-teamspeak"></i></a>
                    <a href="https://truckersmp.com/vtc/11682" target="_blank" class=""><i class="fas fa-truck-pickup"></i></a>
                    <a href="https://trucksbook.eu/company/11661" target="_blank" class=""><i class="fontello icon-trucksbook"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xl-auto mb-md-3">
                <ul class="nav flex-column text-center text-md-right text-xl-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apply') }}">Вступить</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery') }}">Галерея</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kb') }}">База знаний</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('privacy') }}">Политика конфиденциальности</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 col-sm-12 col-xl-auto mb-md-3">
                <ul class="nav flex-column text-center text-md-left text-xl-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('members') }}">Команда</a>
                    </li>
                    <li class="nav-item">
                        @if(\Illuminate\Support\Facades\Auth::user()?->member)
                            <a class="nav-link" href="{{ route('evoque.test') }}">Тест на знание ВТК</a>
                        @else
                            <a class="nav-link" href="https://truckersmp.com/reports/create" target="_blank">Жалоба на водителя</a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('convoy.public') }}">Открытый конвой</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('terms') }}">Правила пользования</a>
                    </li>
                </ul>
            </div>
            <div class="col-xl-auto col-md-6 col-sm-12 copyright">
                <h2>@lang('general.vtc_evoque')</h2>
                <span class="text-muted">Все права защищены &copy; 2016 - {{ date('Y') }}</span>
            </div>
        </div>
    </div>
</footer>
