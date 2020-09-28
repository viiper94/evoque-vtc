@extends('layout.index')

@section('content')

<div class="container pt-5">
    @if($convoy)
        <h1 class="convoy-title text-center pt-5 pb-5">{{ $convoy->title }} {{ $convoy->start_time->isoFormat('L') }}</h1>
        <div class="row mb-5">
            <section class="convoy-info col-md-6 col-sm-12 text-right">
                <ul>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Дата:</p>
                            <h4>{{ $convoy->start_time->isoFormat('LL') }}</h4>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Место старта:</p>
                            <h2>{{ $convoy->start }}</h2>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-map-marker"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Место отдыха:</p>
                            <h2>{{ $convoy->rest }}</h2>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Место финиша:</p>
                            <h2>{{ $convoy->finish }}</h2>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Встречаемся на сервере:</p>
                            <h4>{{ $convoy->server }}</h4>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-server"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Начало конвоя:</p>
                            <h4>{{ $convoy->start_time->subMinutes(30)->format('H:i') }} по МСК</h4>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-4 mb-4">
                            <p>Выезд с места старта:</p>
                            <h4>{{ $convoy->start_time->format('H:i') }} по МСК</h4>
                        </div>
                        <div class="convoy-icon pl-5 pr-4 text-left">
                            <i class="fas fa-clock"></i>
                        </div>
                    </li>
                </ul>
            </section>
            <section class="route col-md-6 col-sm-12">
                <a href="{{ $convoy->route }}" target="_blank"><img src="{{ $convoy->route }}" class="text-shadow-m"></a>
            </section>
        </div>

        <section class="convoy-note pb-5 pt-5 m-auto">
            <hr class="m-auto">
            <blockquote class="blockquote text-center mb-4 mt-4">
                <p class="mb-0">Выставив маршрут, ваша поездка будет <br> комфортной и спокойной!</p>
            </blockquote>
            <hr class="m-auto">
        </section>

        <section class="row convoy-info pt-5 pb-5">
            <ul class="col-md-6 col-sm-12 text-right mb-0">
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Связь ведём через {{ $convoy->communication }}:</p>
                        <a href="{{ $convoy->getCommunicationLink() }}" target="_blank"><h2>{{ $convoy->communication_link }}</h2></a>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        @if($convoy->communication === 'TeamSpeak 3')
                            <i class="fab fa-teamspeak"></i>
                        @elseif($convoy->communication === 'Discord')
                            <i class="fab fa-discord"></i>
                        @endif
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4">
                        <p>Канал на сервере:</p>
                        <h4>{{ $convoy->communication_channel }}</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </li>
            </ul>
            @if($convoy->communication === 'TeamSpeak 3')
                <div class="ts-download col-md-6 col-sm-12 row flex-column align-items-center justify-content-center">
                    <a href="https://teamspeak.com/ru/downloads/" target="_blank" class="btn btn-warning btn-lg mb-2"><i class="fas fa-download"></i> Скачать TeamSpeak 3</a>
                    <button data-toggle="modal" data-target="#ts3-overlay-modal" class="btn btn-outline-warning mt-2">Как настроить оверлей для TS3?</button>
                </div>
            @elseif($convoy->communication === 'Discord')
                <div class="ts-download col-md-6 col-sm-12 row flex-column align-items-center justify-content-center">
                    <a href="https://discord.com/download" target="_blank" class="btn btn-warning btn-lg mb-2"><i class="fas fa-download"></i> Скачать Discord</a>
                </div>
            @endif

        </section>

        <section class="convoy-note pb-5 pt-5 m-auto">
            <hr class="m-auto">
            <blockquote class="blockquote text-center mb-4 mt-4">
                <p class="mb-0">В колонне держим дистанцию не менее 70 метров по TAB и соблюдаем правила TruckersMP.<br>Помимо рации, флуд, мат, а так же фоновая музыка запрещены и в канале TeamSpeak.</p>
            </blockquote>
            <hr class="m-auto">
        </section>

        @if(isset($convoy->trailer))
            <div class="row mb-5 mt-5">
                <section class="convoy-info col-md-6 col-sm-12 text-right">
                    <ul class="mb-0">
                        <li class="row justify-content-end">
                            <div class="mr-4 mb-4">
                                <p>Полуприцеп:</p>
                                <h2>{{ $convoy->trailer }}</h2>
                            </div>
                            <div class="convoy-icon pl-5 pr-4 text-left">
                                <i class="fas fa-trailer"></i>
                            </div>
                        </li>
                        @if(isset($convoy->trailer_tuning))
                            <li class="row justify-content-end">
                                <div class="mr-4 mb-4">
                                    <p>Окрас:</p>
                                    <h4>{{ $convoy->trailer_tuning }}</h4>
                                </div>
                                <div class="convoy-icon pl-5 pr-4 text-left">
                                    <i class="fas fa-star"></i>
                                </div>
                            </li>
                        @endif
                        @if(isset($convoy->trailer_paint))
                            <li class="row justify-content-end">
                                <div class="mr-4 mb-4">
                                    <p>Окрас:</p>
                                    <h4>{{ $convoy->trailer_paint }}</h4>
                                </div>
                                <div class="convoy-icon pl-5 pr-4 text-left">
                                    <i class="fas fa-fill"></i>
                                </div>
                            </li>
                        @endif
                        @if(isset($convoy->cargo))
                            <li class="row justify-content-end">
                                <div class="mr-4 mb-4">
                                    <p>Груз:</p>
                                    <h4>{{ $convoy->cargo }}</h4>
                                </div>
                                <div class="convoy-icon pl-5 pr-4 text-left">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                            </li>
                        @endif
                    </ul>
                </section>
                <section class="route col-md-6 col-sm-12">
                    <a href="{{ $convoy->trailer_image }}" target="_blank"><img src="{{ $convoy->trailer_image }}" class="text-shadow-m"></a>
                </section>
            </div>
        @endif

        <section class="convoy-note pb-5 m-auto">
            <hr class="m-auto">
            <blockquote class="blockquote text-center mb-4 mt-4">
                <p class="mb-0">Один груз — одна большая команда!</p>
            </blockquote>
            <hr class="m-auto">
        </section>
    @else
        <div class="row justify-content-center pt-5 pb-5">
            <h1 class="text-center text-primary m-5">Ближайших открытых конвоев еще нет!</h1>
        </div>
    @endif


</div>

<!-- TS3 overlay modal -->
<div class="modal fade" id="ts3-overlay-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content modal-content-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Установки и настройка оверлея TSNotifier для TeamSpeak 3</h5>
                <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
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

@endsection
