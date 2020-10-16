@extends('layout.index')

@section('title')
    Регламент конвоев | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5 private-convoys">
        @can('manage_convoys')
            <div class="row pt-3 justify-content-center">
                <a href="{{ route('convoys', 'all') }}" class="btn btn-outline-warning">Смотреть все регламенты</a>
            </div>
        @endcan
        @foreach($grouped as $day => $convoys)
            <h1 class="mt-3 text-primary text-center">Регламент на {{ ucfirst($day) }}</h1>
            @foreach($convoys as $convoy)
                <div class="item pt-5 pb-5">
                    <section class="convoy-note pb-3 pt-3 m-auto">
                        <hr class="m-auto">
                        <blockquote class="blockquote text-center mb-4 mt-4">
                            <h2 class="mb-0">{{ $convoy->title }}</h2>
                            @can('manage_convoys')
                                <div class="row pt-3 justify-content-center">
                                    <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-edit"></i> Редактировать</a>
                                </div>
                            @endcan
                        </blockquote>
                        <hr class="m-auto">
                    </section>
                    <div class="row convoy-info pt-5 pb-5">
                        <div class="col-sm-6 pr-md-5 pr-3 text-md-right text-center">
                            <p>Старт:</p>
                            <h3>{{ $convoy->start_city }}</h3>
                            <h5>{{ $convoy->start_company }}</h5>
                            <p class="mt-4">Перерыв:</p>
                            <h3>{{ $convoy->rest_city }}</h3>
                            <h5>{{ $convoy->rest_company }}</h5>
                            <p class="mt-4">Финиш:</p>
                            <h3>{{ $convoy->finish_city }}</h3>
                            <h5>{{ $convoy->finish_company }}</h5>
                            <p class="mt-4">Сбор:</p>
                            <h3 >{{ $convoy->start_time->subMinutes(30)->format('H:i') }} по МСК</h3>
                            <p>Выезд:</p>
                            <h3>{{ $convoy->start_time->format('H:i  ') }} по МСК</h3>
                        </div>
                        <div class="col-sm-6 pl-md-5 pl-3 text-md-left text-center">
                            <p>Сервер:</p>
                            <h3>{{ $convoy->server }}</h3>
                            <p>Ведущий:</p>
                            <h3>{{ $convoy->lead }}</h3>
                            <p>Связь {{ $convoy->communication }}:</p>
                            <h2><a href="{{ $convoy->getCommunicationLink() }}" target="_blank">{{ $convoy->communication_link }}</a></h2>
                            @if($convoy->communication_channel)
                                <p>Канал на сервере:</p>
                                <h3>{{ $convoy->communication_channel }}</h3>
                            @endif
                        </div>
                    </div>
                    @if($convoy->dlc)
                        <h4 class="pt-5 pb-5 text-center"><i class="fas fa-exclamation-triangle text-warning"></i> Для участия требуется DLC {{ implode(', ', $convoy->dlc) }}</h4>
                    @endif
                    <div class="row justify-content-between convoy-info pt-5 pb-5 text-center">
                        <div class="col text-center">
                            <p>Тягач:</p>
                            <h3>{{ $convoy->truck ?? 'Любой' }}</h3>
                        </div>
                        @if($convoy->truck_tuning)
                            <div class="col text-center">
                                <p>Тюнинг:</p>
                                <h3>{{ $convoy->truck_tuning }}</h3>
                            </div>
                        @endif
                        @if($convoy->truck_paint)
                            <div class="col text-center">
                                <p>Окрас:</p>
                                <h3>{{ $convoy->truck_paint }}</h3>
                            </div>
                        @endif
                        @if($convoy->truck_image)
                            <img src="/images/convoys/{{ $convoy->truck_image }}" alt="{{ $convoy->truck }}" class="text-shadow-m">
                        @endif
                    </div>
                    <div class="row justify-content-between convoy-info pt-5">
                        <div class="col text-center">
                            <p>Прицеп:</p>
                            <h3>{{ $convoy->trailer ?? 'Любой' }}</h3>
                            @if($convoy->alt_trailer)
                                <p>Прицеп без ДЛС:</p>
                                <h4>{{ $convoy->alt_trailer }}</h4>
                            @endif
                        </div>
                        @if($convoy->trailer_tuning)
                            <div class="col text-center">
                                <p>Тюнинг:</p>
                                <h3>{{ $convoy->trailer_tuning }}</h3>
                                @if($convoy->alt_trailer_tuning)
                                    <p>Тюнинг прицепа без ДЛС:</p>
                                    <h4>{{ $convoy->alt_trailer_tuning }}</h4>
                                @endif
                            </div>
                        @endif
                        @if($convoy->trailer_paint)
                            <div class="col text-center">
                                <p>Окрас:</p>
                                <h3>{{ $convoy->trailer_paint }}</h3>
                                @if($convoy->alt_trailer_paint)
                                    <p>Окрас прицепа без ДЛС:</p>
                                    <h4>{{ $convoy->alt_trailer_paint }}</h4>
                                @endif
                            </div>
                        @endif
                        @if($convoy->cargo)
                            <div class="col text-center">
                                <p>Груз:</p>
                                <h3>{{ $convoy->cargo }}</h3>
                                @if($convoy->alt_cargo)
                                    <p>Груз без ДЛС:</p>
                                    <h4>{{ $convoy->alt_cargo }}</h4>
                                @endif
                            </div>
                        @endif
                        @if($convoy->trailer_image)
                            <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                                <img src="/images/convoys/{{ $convoy->trailer_image }}" alt="{{ $convoy->trailer }}">
                                @if($convoy->alt_trailer_image)
                                    <img src="/images/convoys/{{ $convoy->alt_trailer_image }}" alt="{{ $convoy->alt_trailer }}">
                                @endif
                            </div>
                        @endif
                    </div>
                    <section class="convoy-note pb-5 pt-5 m-auto">
                        <hr class="m-auto">
                        <blockquote class="blockquote text-center mb-4 mt-4">
                            <h5 class="mb-0">Правила ВТК на конвое:</h5>
                            <ol class="text-left ml-5 mr-1">
                                <li>Канал в рации <b>№7</b> (многочисленный флуд и музыка в рации полностью запрещены).</li>
                                <li>Движение по команде и только колонной, соблюдая ПДД и правила мультиплеера.</li>
                                <li>Рекомендуемая дистанция между машинами <b>70-150 метров</b>.</li>
                                <li>Опережение и обгон c разрешения ведущего.</li>
                                <li>Для того чтобы встать в колонну обязательно разрешение ведущего,<br>
                                    после одобрения встаёте строго перед замыкающим.</li>
                                <li>Обязателен <b>ближний свет фар</b> в любое время суток.</li>
                                <li>Если перед вами кто-то залагал или остановился, то обходите его без остановки.</li>
                                <li>После конвоя переходим в наш ТС для подведения итогов (пока ведущий не отпустит,<br>
                                    из ТС не выходим ибо конвой засчитан не будет!</li>
                            </ol>
                        </blockquote>
                        <hr class="m-auto">
                    </section>
                    <section class="w-100">
                        <h1 class="text-center text-primary">Маршрут конвоя</h1>
                        <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                            @foreach($convoy->route as $item)
                                <img src="/images/convoys/{{ $item }}">
                            @endforeach
                        </div>
                    </section>
                </div>
            @endforeach
        @endforeach
    </div>

@endsection
