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
        @foreach($grouped as $day => $convoys)
            <h1 class="mt-3 text-primary text-center">Регламент на {{ ucfirst($day) }}</h1>
            @foreach($convoys as $convoy)
                <div class="item pt-5 pb-5">
                    <section class="convoy-note pb-3 pt-3 m-auto">
                        <hr class="m-auto">
                        <blockquote class="blockquote text-center mb-4 mt-4">
                            <h2 class="mb-0">{{ $convoy->title }}</h2>
                        </blockquote>
                        <hr class="m-auto">
                    </section>
                    <div class="row convoy-info pt-5">
                        <div class="col-sm-6 pr-5 text-right">
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
                        <div class="col-sm-6 pl-5">
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
                        <h4 class=" mt-5 text-center"><i class="fas fa-exclamation-triangle text-danger"></i> Для участия требуется DLC {{ implode(', ', $convoy->dlc) }}</h4>
                    @endif
                    <div class="row convoy-info pt-5">
                        <div class="col-sm-6 pr-5 text-right">
                            <p>Тягач:</p>
                            <h3>{{ $convoy->truck }}</h3>
                            @if($convoy->truck_image)
                                <a href="/images/convoys/{{ $convoy->truck_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->truck_image }}" alt="{{ $convoy->truck }}" class="text-shadow-m"></a>
                            @endif
                            @if($convoy->truck_tuning)
                                <p>Тюнинг:</p>
                                <h3>{{ $convoy->truck_tuning }}</h3>
                            @endif
                            @if($convoy->truck_paint)
                                <p>Окрас:</p>
                                <h3>{{ $convoy->truck_paint }}</h3>
                            @endif
                        </div>
                        <div class="col-sm-6 pl-5">
                            <p>Прицеп:</p>
                            <h3>{{ $convoy->trailer }}</h3>
                            @if($convoy->trailer_image)
                                <a href="/images/convoys/{{ $convoy->trailer_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->trailer_image }}" alt="{{ $convoy->trailer }}" class="text-shadow-m"></a>
                            @endif
                            @if($convoy->trailer_tuning)
                                <p>Тюнинг:</p>
                                <h3>{{ $convoy->trailer_tuning }}</h3>
                            @endif
                            @if($convoy->trailer_paint)
                                <p>Окрас:</p>
                                <h3>{{ $convoy->trailer_paint }}</h3>
                            @endif
                            @if($convoy->cargo)
                                <p>Груз:</p>
                                <h3>{{ $convoy->cargo }}</h3>
                            @endif
                            @if($convoy->alt_trailer)
                                <p class="mt-4">Прицеп без ДЛС:</p>
                                <h3>{{ $convoy->alt_trailer }}</h3>
                                @if($convoy->alt_trailer_image)
                                    <a href="/images/convoys/{{ $convoy->alt_trailer_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->alt_trailer_image }}" alt="{{ $convoy->alt_trailer }}" class="text-shadow-m"></a>
                                @endif
                                @if($convoy->alt_trailer_tuning)
                                    <p>Тюнинг:</p>
                                    <h3>{{ $convoy->alt_trailer_tuning }}</h3>
                                @endif
                                @if($convoy->alt_trailer_paint)
                                    <p>Окрас:</p>
                                    <h3>{{ $convoy->alt_trailer_paint }}</h3>
                                @endif
                                @if($convoy->alt_cargo)
                                    <p>Груз:</p>
                                    <h3>{{ $convoy->alt_cargo }}</h3>
                                @endif
                            @endif
                        </div>
                        <section class="convoy-note pb-5 pt-5 m-auto">
                            <hr class="m-auto">
                            <blockquote class="blockquote text-center mb-4 mt-4">
                                <h5 class="mb-0">Правила ВТК на конвое:</h5>
                                <ol class="text-left">
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
                            <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                                @foreach($convoy->route as $item)
                                    <img src="/images/convoys/{{ $item }}">
                                @endforeach
                            </div>
                        </section>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

@endsection
