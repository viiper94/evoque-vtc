@extends('layout.index')

@section('title')
    База знаний | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="kb container py-5">
        <h1 class="text-center text-primary mt-3">База знаний</h1>
            @auth
                <h2 class="mt-5 ml-5 kb-section">ВТК EVOQOUE</h2>
                <div class="accordion pb-5" id="vtc">
                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="ets2-paint">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="icon-evoque"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#ets2-paint-content" aria-expanded="false" aria-controls="ets2-paint-content">
                                Оффициальный окрас в ETS2
                            </h2>
                        </div>
                        <div id="ets2-paint-content" class="collapse" aria-labelledby="ets2-paint" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>С 23.07.2020 в компании действует раскраска <b>Canopy</b></p>
                                <p>Чтобы установить данную раскраску необходимо: <br>
                                    1. Перейти по ссылке - <a href="https://bit.ly/2O7v8x7" target="_blank">https://bit.ly/2O7v8x7</a> и нажать <b>Подписаться</b>. <br>
                                    2. Зайти в игру и в настройках профиля применить нужный мод. <br>
                                    3. Нужная покраска будет первой в списке покрасок для каждого тягача. <br>
                                </p>
                                <img src="https://i.imgur.com/vGdV7Xv.png" class="w-100" alt="Оффициальный окрас в ETS2">
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="ats-paint">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-fill-drip"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#ats-paint-content" aria-expanded="false" aria-controls="ats-paint-content">
                                Оффициальный окрас в ATS
                            </h2>
                        </div>
                        <div id="ats-paint-content" class="collapse" aria-labelledby="ats-paint" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>С 23.07.2020 в компании действует раскраска <b>Serenity</b> из DLC Classic Stripes Paint Jobs Pack</p>
                                <p>Чтобы установить данную раскраску необходимо: <br>
                                    1. Перейти по ссылке - <a href="https://bit.ly/3oTNWBc" target="_blank">https://bit.ly/3oTNWBc</a> и нажать <b>Подписаться</b>. <br>
                                    2. Зайти в игру и в настройках профиля применить нужный мод. <br>
                                    3. Нужная покраска будет первой в списке покрасок для каждого тягача. <br>
                                </p>
                                <img src="https://i.imgur.com/yscdQcN.png" class="w-100" alt="Оффициальный окрас в ATS">
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="plate">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-ruler-horizontal"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#plate-content" aria-expanded="false" aria-controls="plate-content">
                                Номерной знак ВТК
                            </h2>
                        </div>
                        <div id="plate-content" class="collapse" aria-labelledby="plate" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>У всех сотрудников компании должен быть установлен номерной знак EVOQUE.</p>
                                <p>Чтобы установить номер нужно:<br>
                                    1) Зайти на сайт <a href="https://www.worldoftrucks.com/en/" target="_blank">https://www.worldoftrucks.com/en/</a><br>
                                    2) Нажимаем на <b>Join now</b> чтобы зарегистрироваться
                                </p>
                                <img src="https://i.imgur.com/4mCo1wR.png" class="w-75" alt="Join now">
                                <p class="mt-3">
                                    3) Нажимаем на регистрацию через Steam, далее следуем инструкции
                                </p>
                                <img src="https://i.imgur.com/a3BBYha.png" class="w-75" alt="steam">
                                <p class="mt-3">
                                    4) После регистрации жмем на <b>Sign in</b> в верхнем правом углу и вводим свои регистрационные данные
                                </p>
                                <img src="https://i.imgur.com/a3BBYha.png" class="w-75" alt="Sign in">
                                <p class="mt-3">
                                    5) Переходим во вкладку <b>PROFILE CUSTOMIZATION</b>, выбираем страну <b>Germany</b>, в соседнем окошке вводим свой номер (например EVOQUE 013, с пробелом), сохраняем всё нажав кнопку <b>Upload</b>
                                </p>
                                <img src="https://i.imgur.com/EugExCd.png" class="w-75" alt="PROFILE CUSTOMIZATION">
                                <p class="mt-3">
                                    6) Далее заходим в игру, на вкладке выбора профиля нажимаем <b>Изменить</b>
                                </p>
                                <img src="https://i.imgur.com/qWyrvoe.png" class="w-75" alt="PROFILE">
                                <p class="mt-3">
                                    7) Вводим регистрационные данные с сайта https://www.worldoftrucks.com/en/<br>
                                    8) Нажимаем <b>Применить изменения</b>
                                </p>
                                <img src="https://i.imgur.com/skmcS86.png" class="w-75" alt="Применить изменения">
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="plates">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-table"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#plates-content" aria-expanded="false" aria-controls="plates-content">
                                Таблица номеров
                            </h2>
                        </div>
                        <div id="plates-content" class="collapse" aria-labelledby="plates" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>Здесь - <a href="https://bit.ly/38zythV" target="_blank">https://bit.ly/38zythV</a> хранятся все занятые номера в компании.<br>
                                    Если вы хотите сменить номер или вам необходимо его придумать, то загляните сюда и подберите любой который не прописан в данной таблице.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="money">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-euro-sign"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#money-content" aria-expanded="false" aria-controls="money-content">
                                Эвики
                            </h2>
                        </div>
                        <div id="money-content" class="collapse" aria-labelledby="money" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>Чтобы получить "Эвики" вам нужно начать или продолжать проводить конвои в нашей компании,
                                    обязательное условие наличие нарисованного маршрута или маршрута сделанного путем скриншота
                                    игровой карты с точками через саму игру. Либо вести нашу колонну на Открытом/совместном конвое другой ВТК.</p>
                                <p>Так же 1 эвик можно получить в конце каждой недели, если вы будете лучшим по посещаемости конвоев.</p>
                                <p>За один проведенный конвой будет начисляться 1 "Эвик". Если наша ВТК в гостях на Открытых
                                    или Совместных конвоях и вы ведущий нашей колонны, то за этот конвой вы получите 0,5 эвика.</p>
                                <p>Заработанные "Эвики" ни каким образом с вас не спишут (для этого есть баллы).</p>
                                <p>Передача другому игроку "Эвиков" <b>ЗАПРЕЩЕНА</b>!</p>
                                <p>Эвики можно обменять на любую игру или дополнение в Стиме!</p>
                                <p class="mb-0">Используемые магазины для обмена Эвиков:</p>
                                <ul class="pl-3">
                                    <li><a href="http://store.steampowered.com" target="_blank">http://store.steampowered.com</a></li>
                                    <li><a href="http://playo.ru" target="_blank">http://playo.ru</a></li>
                                    <li><a href="http://plati.ru" target="_blank">http://plati.ru</a></li>
                                    <li><a href="https://www.g2a.com" target="_blank">https://www.g2a.com</a></li>
                                    <li>Другие магазины по-договорённости</li>
                                </ul>
                                <p>Формула расчета количества Эвиков на желаемую игру (DLC): <br>
                                    Стоимость игры (DLC) в рублях / 20 = нужное количество Эвиков (округление после
                                    запятой в большую сторону, пример: 15,6 = 16 или 12,1 = 12,5)</p>
                                <p>Формула работает на момент покупки товара (следите за скидками).</p>
                                <p>Все вопросы и пожелания в личку. Ответственный Виталя 43RUS.<br>
                                    Дерзайте всё в ваших руках. Это не лотерея это шанс заработать просто играя в любимую игру)).</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="donate">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-hand-holding-usd"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#donate-content" aria-expanded="false" aria-controls="donate-content">
                                Помощь ВТК
                            </h2>
                        </div>
                        <div id="donate-content" class="collapse" aria-labelledby="donate" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <p>Ежемесячно тратиться 100 рублей на оплату интерактивной шапки в Открытой группе + обмен Эвиков, рейтинговые грузоперевозки и соревнования.</p>
                                <p>Сразу скажу, вас никто не обманет ибо в моей честности уже не усомнились многие из вас.<br>
                                    Ни на какие личные нужды эти финансы не уйдут, всё пойдет на нужды ВТК.</p>
                                <p>В качестве благодарности за денежный вклад, каждые ваши 20 рублей будут обменены на 1 балл в таблице.</p>
                                <p><b>4276 2700 1607 7289</b> - Сбербанк<br>
                                    <b>410011170616417</b> - Яндекс кошелек<br>
                                    <b>+79123703612</b> - Qiwi<br>
                                    <b>5100 6914 8186 6022</b> - Raiffeisen bank</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-dark mb-5">
                        <div class="card-header row pb-0" id="links">
                            <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-external-link-alt"></i></h1>
                            <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#links-content" aria-expanded="false" aria-controls="links-content">
                                Полезные ссылки
                            </h2>
                        </div>
                        <div id="links-content" class="collapse" aria-labelledby="links" data-parent="#vtc">
                            <div class="card-body pl-5">
                                <h6>ВТК EVOQUE</h6>
                                <ol class="pl-4">
                                    <li><a href="https://evoque.team" target="_blank">https://evoque.team</a> - Наш сайт</li>
                                    <li><a href="https://vk.com/evoquevtc" target="_blank">https://vk.com/evoquevtc</a> - Открытая группа ВК</li>
                                    <li><a href="https://bit.ly/2O7v8x7" target="_blank">https://bit.ly/2O7v8x7</a> - Официальный мод VTC EVOQUE в ETS2</li>
                                    <li><a href="https://bit.ly/31THxg3" target="_blank">https://bit.ly/31THxg3</a> - Официальный мод VTC EVOQUE в ATS</li>
                                    <li><a href="https://bit.ly/2Cj13rS" target="_blank">https://bit.ly/2Cj13rS</a> - Карта участников ВТК EVOQUE</li>
                                    <li><a href="https://bit.ly/2Cd5Hra" target="_blank">https://bit.ly/2Cd5Hra</a> - Обои с символикой EVOQUE</li>
                                </ol>
                                <h6 class="pt-3">TruckersMP</h6>
                                <ol class="pl-4">
                                    <li><a href="https://truckersmp.com/download" target="_blank">https://truckersmp.com/download</a> - Скачать МП</li>
                                    <li><a href="https://bit.ly/3gDlfTU" target="_blank">https://bit.ly/3gDlfTU</a> - Правила TruckersMP на русском языке</li>
                                    <li><a href="https://bit.ly/3ebjhdF" target="_blank">https://bit.ly/3ebjhdF</a> - Полезный гайд по созданию репортов</li>
                                    <li><a href="https://ets2map.com" target="_blank">https://ets2map.com</a> - Онлайн-карта игроков</li>
                                    <li><a href="https://truckersmp.com/reports/create" target="_blank">https://truckersmp.com/reports/create</a> - Отправить репорт на игрока</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                </div>
            @endauth

            <h2 class="mt-5 ml-5 kb-section">Связь</h2>
            <div class="accordion pb-5" id="communication">
{{--                <div class="card card-dark mb-5">--}}
{{--                    <div class="card-header row pb-0" id="discord">--}}
{{--                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fab fa-discord"></i></h1>--}}
{{--                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#discord-content" aria-expanded="false" aria-controls="discord-content">--}}
{{--                            Как скачать Дискорд?--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                    <div id="discord-content" class="collapse" aria-labelledby="discord" data-parent="#communication">--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="card card-dark mb-5">--}}
{{--                    <div class="card-header row pb-0" id="ts3">--}}
{{--                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fab fa-teamspeak"></i></h1>--}}
{{--                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#ts3-content" aria-expanded="false" aria-controls="ts3-content">--}}
{{--                            Как скачать Team Speak 3?--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                    <div id="ts3-content" class="collapse" aria-labelledby="ts3" data-parent="#communication">--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="card card-dark mb-5">
                    <div class="card-header pb-0 row" id="ts3-overlay">
                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-layer-group"></i></h1>
                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#ts3-overlay-content" aria-expanded="false" aria-controls="ts3-overlay-content">
                            Оверлей для TS3
                        </h2>
                    </div>
                    <div id="ts3-overlay-content" class="collapse" aria-labelledby="ts3-overlay" data-parent="#communication">
                        <div class="card-body pl-md-5 pl-sm-1">
                            <ol class="pl-4">
                                <li>
                                    <p>Скачайте с сайта <a href="http://tsnotifier.eu/" target="_blank">http://tsnotifier.eu/</a> последнюю версию оверлея.</p>
                                </li>
                                <li>
                                    <p>Запустите установщик, выбираем путь установки и устанавливаем. <br>
                                        Во время установки у вас появятся два окна с подтверждением установки от Teamspeak, выбираете Yes дважды.</p>
                                </li>
                                <li>
                                    <p>Запустите Teamspeak, затем TSNotifier.</p>
                                </li>
                                <li>
                                    <p>Ищите программу TSNotifier в трее (она обозначена как TS).<br>
                                        <img src="/assets/img/modals/ts3-overlay4.jpg"></p>
                                </li>
                                <li>
                                    <p>Кликните правой клавишей мыши по ней и ищите строчку <b>Edit gamesettings.ini</b> и один раз кликните левой кнопкой мыши по ней. Должен открыться блокнот.<br>
                                        <img src="/assets/img/modals/ts3-overlay5.jpg"></p>
                                </li>
                                <li>
                                    <p>Ищите 2 строчки: <br>
                                        <code>
                                            [eurotrucks2.exe] <br>
                                            game.enabled=0 <br>
                                        </code>
                                        "0"- исправьте на "1" и выходите с сохранением.</p>
                                </li>
                                <li>
                                    <p>Рекомендуем выставить настройку «Запуска вместе с TS». Для этого правой кнопкой мышки кликните по иконке TSNotifier, затем выберете вкладку General и кликните на строчку «Start with TS3».</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="mt-5 ml-5 kb-section">ETS2 и ATS</h2>
            <div class="accordion pb-5" id="games">
                <div class="card card-dark mb-5">
                    <div class="card-header row pb-0" id="console">
                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-terminal"></i></h1>
                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#console-content" aria-expanded="false" aria-controls="console-content">
                            Активация консоли
                        </h2>
                    </div>
                    <div id="console-content" class="collapse" aria-labelledby="console" data-parent="#games">
                        <div class="card-body pl-5">
                            <p>Что бы активировать консоль в игре нужно:</p>
                            <ol class="pl-4">
                                <li>Переходим в папку <b>Мои документы/Euro Truck Simulator 2</b></li>
                                <li>Открываем файл <b>config.cfg</b> любым текстовым редактором</li>
                                <li>Ищем строки<br>
                                    <code>
                                        uset g_console "0"<br>
                                        uset g_developer "0"<br>
                                    </code>
                                    <i>Используйте поиск по файлу (CTRL+F)</i>
                                </li>
                                <li>В найденных строках меняем <b>0</b> на <b>1</b>. Сохраняем файл.</li>
                                <li>Заходим в одиночный режим игры</li>
                                <li>В игре нажимаем <b>` (ё)</b> - тильда, вызвав тем самым игровую консоль.</li>
                            </ol>
                        </div>
                    </div>
                </div>

{{--                <div class="card card-dark mb-5">--}}
{{--                    <div class="card-header row pb-0" id="cargo">--}}
{{--                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-truck-loading"></i></h1>--}}
{{--                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#cargo-content" aria-expanded="false" aria-controls="cargo-content">--}}
{{--                            Как искать груз на конвой?--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                    <div id="cargo-content" class="collapse" aria-labelledby="cargo" data-parent="#games">--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>

{{--            <h2 class="mt-5 ml-5 kb-section">TruckersMP</h2>--}}
{{--            <div class="accordion" id="truckersmp">--}}
{{--                <div class="card card-dark mb-5">--}}
{{--                    <div class="card-header row pb-0" id="tmp-download">--}}
{{--                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-download"></i></h1>--}}
{{--                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#tmp-download-content" aria-expanded="false" aria-controls="tmp-download-content">--}}
{{--                            Как скачать клиент TruckersMP?--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                    <div id="tmp-download-content" class="collapse" aria-labelledby="tmp-download" data-parent="#truckersmp">--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="card card-dark mb-5">--}}
{{--                    <div class="card-header row pb-0" id="promods">--}}
{{--                        <h1 class="kb-icon pr-5 pl-sm-1"><i class="fas fa-map"></i></h1>--}}
{{--                        <h2 class="kb-title pl-md-5 pl-sm-1" type="button" data-toggle="collapse" data-target="#promods-content" aria-expanded="false" aria-controls="promods-content">--}}
{{--                            Как скачать карту ProMods?--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                    <div id="promods-content" class="collapse" aria-labelledby="promods" data-parent="#truckersmp">--}}
{{--                        <div class="card-body">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

    </div>

@endsection
