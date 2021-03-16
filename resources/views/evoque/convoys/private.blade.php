@extends('layout.index')

@section('title')
    Регламент конвоев | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')

    <div class="container py-5 private-convoys">
        @can('viewAny', \App\Convoy::class)
            <div class="row pt-5 justify-content-center">
                @if($all)
                    <a href="{{ route('convoys.private') }}" class="btn btn-outline-danger btn-sm">Вернуть текущие регламенты</a>
                @else
                    <a href="{{ route('convoys.private', 'all') }}" class="btn btn-outline-success btn-sm">Показать будущие регламенты</a>
                @endif
            </div>
        @endcan
        @foreach($grouped as $day => $convoys)
            <h1 class="pt-5 text-primary text-center">Регламент на {{ ucfirst($day) }}</h1>
                <div class="accordion text-shadow-m mb-5" id="convoysAccordion-{{ $loop->index }}">
                    @foreach($convoys as $convoy)
                        <div class="card card-dark">
                            <div class="card-header m-0 row {{ !$convoy->isUpcoming() ? '' : 'upcoming' }}" id="convoy-{{ $convoy->id }}-header"
                                 data-toggle="collapse" data-target="#convoy-{{ $convoy->id }}-info"
                                 aria-expanded="false" aria-controls="convoy-{{ $convoy->id }}-info">
                                <h4 class="m-auto">{{ $convoy->start_time->format('H:i') }} - {{ $convoy->title }}</h4>
                                @can('update', \App\Convoy::class)
                                    <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                @endcan
                            </div>
                            <div id="convoy-{{ $convoy->id }}-info" class="collapse" aria-labelledby="convoy-{{ $convoy->id }}-header" data-parent="#convoysAccordion-{{ $loop->parent->index }}">
                                <div class="card-body">
                                    @if($convoy->isFulfilled())
                                        <div class="row convoy-info pt-3 pb-5">
                                            <div class="col-sm-6 pr-md-5 pr-3 text-md-right text-center">
                                                <p class="text-muted">Старт:</p>
                                                <h3>{{ $convoy->start_city }}</h3>
                                                <h5>{{ $convoy->start_company }}</h5>
                                                <p class="mt-4 text-muted">Перерыв:</p>
                                                <h3>{{ $convoy->rest_city }}</h3>
                                                <h5>{{ $convoy->rest_company }}</h5>
                                                <p class="mt-4 text-muted">Финиш:</p>
                                                <h3>{{ $convoy->finish_city }}</h3>
                                                <h5>{{ $convoy->finish_company }}</h5>
                                                <p class="mt-4 text-muted">Сбор:</p>
                                                <h3 >{{ $convoy->start_time->subMinutes(30)->format('H:i') }} по МСК</h3>
                                                <p class="text-muted">Выезд:</p>
                                                <h3>{{ $convoy->start_time->format('H:i  ') }} по МСК</h3>
                                            </div>
                                            <div class="col-sm-6 pl-md-5 pl-3 text-md-left text-center">
                                                <p class="text-muted">Сервер:</p>
                                                <h3>{{ $convoy->server }}</h3>
                                                <p class="text-muted">Ведущий:</p>
                                                <h3>
                                                    {{ $convoy->lead }}&nbsp;
                                                    @if($convoy->leadMember && $convoy->leadMember->user->vk)
                                                        <a href="{{ $convoy->leadMember->user->vk }}" target="_blank"><i class="fab fa-vk"></i></a>
                                                    @endif
                                                </h3>
                                                <p class="text-muted">Связь {{ $convoy->communication }}:</p>
                                                <h3><a href="{{ $convoy->getCommunicationLink() }}" target="_blank">{{ $convoy->communication_link }}</a></h3>
                                                @if($convoy->communication_channel)
                                                    <p class="text-muted">Канал на сервере:</p>
                                                    <h3>{{ $convoy->communication_channel }}</h3>
                                                @endif
                                            </div>
                                        </div>
                                        @if($convoy->dlc)
                                            <h4 class="pt-5 pb-5 text-center"><i class="fas fa-exclamation-triangle text-warning"></i> Для участия требуется {{ implode(', ', $convoy->dlc) }}</h4>
                                        @endif
                                        <div class="row convoy-info pt-5">
                                            <div class="col-sm-6 pr-md-5 pr-3 text-md-right text-center">
                                                <p class="text-muted">Тягач:</p>
                                                <h3>{{ $convoy->truck ?? 'Любой' }}</h3>
                                                @if($convoy->truck_image)
                                                    <a href="/images/convoys/{{ $convoy->truck_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->truck_image }}" alt="{{ $convoy->truck }}" class="text-shadow-m"></a>
                                                @endif
                                                @if($convoy->truck_tuning)
                                                    <p class="text-muted">Тюнинг:</p>
                                                    <h3>{{ $convoy->truck_tuning }}</h3>
                                                @endif
                                                @if($convoy->truck_paint)
                                                    <p class="text-muted">Окрас:</p>
                                                    <h3>{{ $convoy->truck_paint }}</h3>
                                                @endif
                                            </div>
                                            <div class="col-sm-6 pl-md-5 pl-3 text-md-left text-center">
                                                <p class="text-muted">Прицеп:</p>
                                                <h3>{{ $convoy->trailer ?? 'Любой' }}</h3>
                                                @if($convoy->trailer_image)
                                                    <a href="/images/convoys/{{ $convoy->trailer_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->trailer_image }}" alt="{{ $convoy->trailer }}" class="text-shadow-m"></a>
                                                @endif
                                                @if($convoy->trailer_tuning)
                                                    <p class="text-muted">Тюнинг:</p>
                                                    <h3>{{ $convoy->trailer_tuning }}</h3>
                                                @endif
                                                @if($convoy->trailer_paint)
                                                    <p class="text-muted">Окрас:</p>
                                                    <h3>{{ $convoy->trailer_paint }}</h3>
                                                @endif
                                                @if($convoy->cargo)
                                                    <p class="text-muted">Груз:</p>
                                                    <h3>{{ $convoy->cargo }}</h3>
                                                @endif
                                                @if($convoy->alt_trailer)
                                                    <p class="mt-5 text-muted">Прицеп без ДЛС:</p>
                                                    <h3>{{ $convoy->alt_trailer }}</h3>
                                                    @if($convoy->alt_trailer_image)
                                                        <a href="/images/convoys/{{ $convoy->alt_trailer_image }}" target="_blank"><img src="/images/convoys/{{ $convoy->alt_trailer_image }}" alt="{{ $convoy->alt_trailer }}" class="text-shadow-m"></a>
                                                    @endif
                                                    @if($convoy->alt_trailer_tuning)
                                                        <p class="text-muted">Тюнинг:</p>
                                                        <h3>{{ $convoy->alt_trailer_tuning }}</h3>
                                                    @endif
                                                    @if($convoy->alt_trailer_paint)
                                                        <p class="text-muted">Окрас:</p>
                                                        <h3>{{ $convoy->alt_trailer_paint }}</h3>
                                                    @endif
                                                    @if($convoy->alt_cargo)
                                                        <p class="text-muted">Груз:</p>
                                                        <h3>{{ $convoy->alt_cargo }}</h3>
                                                    @endif
                                                @endif
                                            </div>
                                            <section class="col-12 convoy-note py-5 m-auto">
                                                <hr class="m-auto">
                                                <blockquote class="blockquote text-center my-5">
                                                    <h5 class="mb-0">Правила ВТК на конвое:</h5>
                                                    <ol class="text-left ml-5 mr-1 px-md-5">
                                                        <li>Канал в рации <b>№7</b> (многочисленный флуд и музыка в рации полностью запрещены).</li>
                                                        <li>Движение по команде и только колонной, соблюдая ПДД и правила мультиплеера.</li>
                                                        <li>Рекомендуемая дистанция между машинами <b>70-150 метров</b>.</li>
                                                        <li>Опережение и обгон c разрешения ведущего.</li>
                                                        <li>Для того чтобы встать в колонну обязательно разрешение ведущего,<br>
                                                            после одобрения встаёте строго перед замыкающим.</li>
                                                        <li>Обязателен <b>ближний свет фар</b> в любое время суток.</li>
                                                        <li>Если перед вами кто-то залагал или остановился, то обходите его без остановки.</li>
                                                        <li>После завершения конвоя другой ВТК переходим на наш сервер для подведения итогов
                                                            (не выходим пока ведущий не отпустит, ибо конвой засчитан не будет!)</li>
                                                    </ol>
                                                </blockquote>
                                                <hr class="m-auto">
                                            </section>
                                            @if(isset($convoy->comment))
                                                <section class="col-12 convoy-note m-auto pb-5">
                                                    <blockquote class="blockquote pb-5 text-left markdown-content mx-2 ml-md-5 px-md-3">
                                                        @markdown($convoy->comment)
                                                    </blockquote>
                                                    <hr class="m-auto">
                                                </section>
                                            @endif
                                        </div>
                                        @if($convoy->route)
                                            <section class="w-100">
                                                <h1 class="text-center">Маршрут конвоя</h1>
                                                <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
                                                    @foreach($convoy->route as $item)
                                                        <img src="/images/convoys/{{ $item }}">
                                                    @endforeach
                                                </div>
                                            </section>
                                        @endif
                                    @else
                                        <h3 class="text-primary text-center">Еще нет регламента</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        @endforeach
        {{ $paginator->links('layout.pagination') }}
    </div>

@endsection
