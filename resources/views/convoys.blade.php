@extends('layout.index')

@section('content')

<div class="container pt-5">
    <h1 class="convoy-title text-center pt-5 pb-5">Открытый конвой ВТК EVOQUE 01.09.20</h1>
    <div class="row mb-5">
        <section class="convoy-info col-md-6 col-sm-12 text-right">
            <ul>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Место старта:</p>
                        <h2>Глазго (Glasgow) - WGCC</h2>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-map-marker"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Место отдыха:</p>
                        <h2>Дувр (Dover) - eAcres</h2>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Место финиша:</p>
                        <h2>Реймс (Reims) - eAcres</h2>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Встречаемся на сервере:</p>
                        <h4>Simulation 1</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-server"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Начало конвоя:</p>
                        <h4>19:00 по МСК</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Выезд с места старта:</p>
                        <h4>19:30 по МСК</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-clock"></i>
                    </div>
                </li>
            </ul>
        </section>
        <section class="route col-md-6 col-sm-12">
            <a href="/images/convoys/1.jpg" target="_blank"><img src="/images/convoys/1.jpg" class="text-shadow-m"></a>
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
                    <p>Связь ведём через TeamSpeak 3:</p>
                    <a href="http://invite.teamspeak.com/evoque.ts3srv.ru" target="_blank"><h2>evoque.ts3srv.ru</h2></a>
                </div>
                <div class="convoy-icon pl-5 pr-4 text-left">
                    <i class="fab fa-teamspeak"></i>
                </div>
            </li>
            <li class="row justify-content-end">
                <div class="mr-4">
                    <p>Канал на сервере:</p>
                    <h4>Открытый конвой ВТК "EVOQUE"</h4>
                </div>
                <div class="convoy-icon pl-5 pr-4 text-left">
                    <i class="fas fa-list-alt"></i>
                </div>
            </li>
        </ul>
        <div class="ts-download col-md-6 col-sm-12 row flex-column align-items-center justify-content-center">
            <a href="https://teamspeak.com/ru/downloads/" target="_blank" class="btn btn-warning btn-lg mb-2">Скачать TeamSpeak 3</a>
            <a href="#" class="btn btn-outline-warning mt-2">Как настроить оверлей для TS3?</a>
        </div>
    </section>

    <section class="convoy-note pb-5 pt-5 m-auto">
        <hr class="m-auto">
        <blockquote class="blockquote text-center mb-4 mt-4">
            <p class="mb-0">В колонне держим дистанцию не менее 70 метров по TAB и соблюдаем правила TruckersMP.<br>Помимо рации, флуд, мат, а так же фоновая музыка запрещены и в канале TeamSpeak.</p>
        </blockquote>
        <hr class="m-auto">
    </section>

    <div class="row mb-5 mt-5">
        <section class="convoy-info col-md-6 col-sm-12 text-right">
            <ul class="mb-0">
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Полуприцеп:</p>
                        <h2>Шторный</h2>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-trailer"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Окрас:</p>
                        <h4>Trameri</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-fill"></i>
                    </div>
                </li>
                <li class="row justify-content-end">
                    <div class="mr-4 mb-4">
                        <p>Груз:</p>
                        <h4>&mdash;</h4>
                    </div>
                    <div class="convoy-icon pl-5 pr-4 text-left">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                </li>
            </ul>
        </section>
        <section class="route col-md-6 col-sm-12">
            <a href="/images/convoys/1-trailer.jpg" target="_blank"><img src="/images/convoys/1-trailer.jpg" class="text-shadow-m"></a>
        </section>
    </div>

    <section class="convoy-note pb-5 m-auto">
        <hr class="m-auto">
        <blockquote class="blockquote text-center mb-4 mt-4">
            <p class="mb-0">Один груз — одна большая команда!</p>
        </blockquote>
        <hr class="m-auto">
    </section>

</div>

@endsection
