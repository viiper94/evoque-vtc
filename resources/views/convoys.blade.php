@extends('layout.index')

@section('title')
    Открытый конвой @lang('general.vtc_evoque') в TruckersMP
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('meta')
    <meta name="description" content="ВТК EVOQUE - Опытная, динамично развивающаяся виртуальная транспортная компания,
        которая занимается грузоперевозками в мире TruckersMP, проводит регулярные открытые конвои по мультиплееру ETS2 и ATS.">
    <meta name="keywords" content="втк, конвой, открытые конвои, открытый конвой, совместные поездки, покатушки,
        перевозки, грузоперевозки, виртуальная транспортная компания, truckersmp, truckers mp, ets2mp, atsmp, ets2 mp,
        euro truck simulator 2, american truck simulator, ets2, ats, multiplayer, мультиплеер, симулятор дальнобойщика,
        вступить в втк, втупить в компанию">
    <meta property="og:title" content="Открытый конвой @lang('general.vtc_evoque')">
    <meta property="og:type" content="article">
    @if($convoy)
        <meta property="og:image" content="{{ \Illuminate\Support\Facades\URL::to('/') }}/images/convoys/{{ $convoy->route[0] }}">
    @endif
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:site_name" content="ВТК EVOQUE">
@endsection

@section('content')

<div class="container pt-5">
    @include('layout.alert')
    @if($convoy)
        <h1 class="convoy-title text-center pt-5">Открытый конвой @lang('general.vtc_evoque') {{ $convoy->start_time->isoFormat('L') }}</h1>
        @can('update', \App\Convoy::class)
            <div class="row justify-content-center">
                <a href="{{ route('convoy.discord', $convoy->id) }}" class="btn btn-sm btn-outline-info m-1"
                    onclick="return confirm('Запостить регламент?')">Запостить регламент в Дискорд</a>
                <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-sm btn-outline-warning m-1">Редактировать</a>
            </div>
        @endcan
        <div class="row mb-5 pt-5">
            <section class="convoy-info col-md-6 col-sm-12 text-right">
                <ul>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Дата:</p>
                            <h4>{{ $convoy->start_time->isoFormat('LL') }}</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Место старта:</p>
                            <h2>{{ $convoy->start_city }}</h2>
                            <h4>{{ $convoy->start_company }}</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pr-4 pb-4 text-left d-flex align-items-center">
                            <i class="fas fa-map-marker"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Место отдыха:</p>
                            <h2>{{ $convoy->rest_city }}</h2>
                            <h4>{{ $convoy->rest_company }}</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Место финиша:</p>
                            <h2>{{ $convoy->finish_city }}</h2>
                            <h2>{{ $convoy->finish_company }}</h2>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Встречаемся на сервере:</p>
                            <h4>{{ $convoy->server }}</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-server"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Начало конвоя:</p>
                            <h4>{{ $convoy->start_time->subMinutes(30)->format('H:i') }} по МСК</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </li>
                    <li class="row justify-content-end">
                        <div class="mr-3 mr-md-4 mb-4">
                            <p>Выезд с места старта:</p>
                            <h4>{{ $convoy->start_time->format('H:i') }} по МСК</h4>
                        </div>
                        <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                            <i class="fas fa-clock"></i>
                        </div>
                    </li>
                    @if($convoy->cargoman)
                        <li class="row justify-content-end">
                            <div class="mr-3 mr-md-4 mb-4">
                                <p>CargoMan:</p>
                                <h4>{{ $convoy->cargoman }}</h4>
                                <p>
                                    <a class="text-muted" href="{{ route('kb.view', 18) }}" target="_blank">Что это такое?</a>
                                </p>
                            </div>
                            <div class="convoy-icon pl-md-5 pl-4 pb-4 pr-4 text-left d-flex align-items-center">
                                <i class="fas fa-plus"></i>
                            </div>
                        </li>
                    @endif
                </ul>
            </section>
            <section class="route col-md-6 col-sm-12">
                <div class="fotorama w-100 text-shadow-m" data-allowfullscreen="true" data-nav="thumbs">
                    @foreach($convoy->route as $item)
                        <img src="/images/convoys/{{ $item }}">
                    @endforeach
                </div>
            </section>
        </div>

        @if($convoy->dlc)
            <section>
                <h4 class="mt-5 text-center"><i class="fas fa-exclamation-triangle text-warning"></i> Для участия требуется {{ implode(', ', $convoy->dlc) }}</h4>
            </section>
        @endif

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
                        <a href="{{ $convoy->getCommunicationLink() }}" target="_blank"><h4>{{ $convoy->communication_link }}</h4></a>
                    </div>
                    <div class="convoy-icon pl-md-5 px-3 pb-4 px-sm-4 text-left d-flex align-items-center">
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
                    <div class="convoy-icon pl-md-5 px-3 pb-4 px-sm-4 text-left d-flex align-items-center">
                        <i class="fas fa-list-alt"></i>
                    </div>
                </li>
            </ul>
            @if($convoy->communication === 'TeamSpeak 3')
                <div class="ts-download col-md-6 col-sm-12 row flex-column align-items-center justify-content-center">
                    <a href="https://teamspeak.com/ru/downloads/" target="_blank" class="btn btn-warning btn-lg mb-2"><i class="fas fa-download"></i> Скачать TeamSpeak 3</a>
                    <a href="{{ route('kb.view', 8)  }}" class="btn btn-outline-warning mt-2" target="_blank">Как настроить оверлей для TS3?</a>
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
                <p class="mb-0">В колонне держим дистанцию не менее 70 метров по TAB и соблюдаем правила TruckersMP.<br>Помимо рации, флуд, мат, а так же фоновая музыка запрещены и в голосовом канале Discord.</p>
            </blockquote>
            <hr class="m-auto">
        </section>

        @if(isset($convoy->truck) && $convoy->truck_public)
            <div class="row mb-5 mt-5">
                <section class="convoy-info col-md-6 col-sm-12 text-right">
                    <ul class="mb-0">
                        <li class="row justify-content-end">
                            <div class="mr-4 mb-4">
                                <p>Тягач:</p>
                                <h2>{{ $convoy->truck }}</h2>
                            </div>
                            <div class="convoy-icon pl-5 pr-4 pb-4 text-left d-flex align-items-center">
                                <i class="fas fa-truck-pickup"></i>
                            </div>
                        </li>
                    </ul>
                </section>
                <section class="route col-md-6 col-sm-12">
                    <div class="fotorama w-100 text-shadow-m" data-allowfullscreen="true" data-nav="thumbs">
                        <img src="/images/convoys/{{ $convoy->truck_image }}">
                    </div>
                </section>
            </div>
        @endif

        @if(isset($convoy->trailer) && $convoy->trailer_public)
            <div class="row mb-5 mt-5">
                <section class="convoy-info col-md-6 col-sm-12 text-right">
                    <ul class="mb-0">
                        <li class="row justify-content-end">
                            <div class="mr-4 mb-4">
                                <p>Прицеп:</p>
                                <h2>{{ $convoy->trailer }}</h2>
                            </div>
                            <div class="convoy-icon pl-5 pr-4 pb-4 text-left d-flex align-items-center">
                                <i class="fas fa-trailer"></i>
                            </div>
                        </li>
                        @if(isset($convoy->cargo))
                            <li class="row justify-content-end">
                                <div class="mr-4 mb-4">
                                    <p>Груз:</p>
                                    <h4>{{ $convoy->cargo }}</h4>
                                </div>
                                <div class="convoy-icon pl-5 pr-4 pb-4 text-left d-flex align-items-center">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                            </li>
                        @endif
                    </ul>
                </section>
                <section class="route col-md-6 col-sm-12">
                    <div class="fotorama w-100 text-shadow-m" data-allowfullscreen="true" data-nav="thumbs">
                        <img src="/images/convoys/{{ $convoy->trailer_image }}">
                    </div>
                </section>
            </div>
            <section class="convoy-note pb-5 m-auto">
                <hr class="m-auto">
                <blockquote class="blockquote text-center mb-4 mt-4">
                    <p class="mb-0">Один груз — одна большая команда!</p>
                </blockquote>
                <hr class="m-auto">
            </section>
        @endif

        @if(isset($convoy->links['public']) && $convoy->links['public'] === 'on')
            <section class="apply mt-5 mb-5">
                <div class="apply-wrapper text-center">
                    <h3 class="mb-3">Подпишись на конвой!</h3>
                    @foreach($convoy->links as $to => $link)
                        @if($to === 'public' || $link === null) @continue @endif
                        <a class="btn btn-outline-warning btn-lg" href="{{ $link }}" target="_blank">{{ $to }}</a>
                    @endforeach
                </div>
            </section>
        @endif

    @else
        <div class="row justify-content-center pt-5 pb-5">
{{--            <h1 class="text-center text-primary m-5">На летний период проведение Открытых конвоев в нашей ВТК приостановлено. Будем рады видеть Вас в сентябре!</h1>--}}
            <h1 class="text-center text-primary m-5">Мы проводим открытые мероприятия по вторникам. Следующий конвой уже готовится!</h1>
        </div>
    @endif

</div>

@endsection
