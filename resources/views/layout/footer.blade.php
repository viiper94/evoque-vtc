<footer>
    <div class="container pt-5 pb-5 pl-5 pr-5">
        <div class="row">
            <div class="col-md-3 col-sm-12 row flex-column justify-content-center align-items-center ml-0">
                <h2>@lang('general.vtc_evoque')</h2>
                <span class="copyright">Все права защищены &copy; {{ date('Y') }}</span>
            </div>
            <div class="col-md-3 col-sm-12">
                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('apply') }}">Вступить</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('rules') }}">Правила</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">О нас</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12">
                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('members') }}">Команда</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Жалоба на водителя</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('convoys', 'public') }}">Открытый конвой</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-12 row flex-column justify-content-center align-items-center ml-0">
                <h2>Наши контакты</h2>
                <div class="contacts row">
                    <div class="vk">
                        <a href="https://vk.com/evoquevtc" target="_blank" class="pl-3 pr-2"><i class="fab fa-vk"></i></a>
                    </div>
                    <div class="discord">
                        <a href="https://discord.com/invite/Gj53a8d" target="_blank" class="pl-3 pr-2"><i class="fab fa-discord"></i></a>
                    </div>
                    <div class="teamspeak">
                        <a href="http://invite.teamspeak.com/evoque.ts3srv.ru" target="_blank" class="pl-3 pr-2"><i class="fab fa-teamspeak"></i></a>
                    </div>
                    <div class="teamspeak">
                        <a href="https://truckersmp.com/vtc/11682" target="_blank" class="pl-3 pr-2"><i class="fas fa-truck-pickup"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
