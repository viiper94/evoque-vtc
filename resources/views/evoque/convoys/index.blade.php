@extends('layout.index')

@section('title')
        Все конвои | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')
    <div class="container-fluid pt-5">
        @include('layout.alert')
        <h1 class="mt-3 text-primary ml-3 text-center">
            Все конвои
            @can('create', \App\Convoy::class)
                <a href="{{ route('evoque.admin.convoy.add') }}" class="btn btn-outline-warning"><i class="fas fa-plus"></i></a>
            @endcan
        </h1>
        <div class="convoys mt-3 mb-5">
            @foreach($convoys as $day => $convoys_per_day)
                @php
                    $c_day = \Carbon\Carbon::parse($day);
                @endphp
                <div class="day row flex-column flex-md-row mx-0">
                    <h1 class="@if($c_day->isToday())text-primary @elseif($c_day->isFuture()) upcoming @endif text-md-right
                        text-center col-md-auto my-md-3 mt-3 p-0 align-self-center convoys-day">
                        {{ $c_day->format('d.m').' '.$c_day->isoFormat('dd') }}
                    </h1>
                    <div class="col-md convoys-list m-0 p-0 my-0 my-md-3 mx-md-5 px-md-0">
                        @foreach($convoys_per_day as $convoy)
                            <div class="card card-dark text-shadow-m my-md-2 ml-md-5 col-12 px-md-0
                                @if($convoy->booking && !$convoy->visible) border-danger
                                @elseif(!$convoy->start_city) border-info @endif">
                                <div class="card-header row mb-0 mx-0" id="convoy-{{ $convoy->id }}-header"
                                     data-toggle="collapse" data-target="#convoy-{{ $convoy->id }}-info"
                                     aria-expanded="false" aria-controls="convoy-{{ $convoy->id }}-info">
                                    <div class="col px-0 app-title">
                                        <h5 class="m-auto text-center @if($convoy->isUpcoming())upcoming @endif">
                                            {{ $convoy->title }}
                                        </h5>
                                    </div>
                                    @can('update', \App\Convoy::class)
                                        <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                            <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                                <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="dropdown-item"><i class="fas fa-edit"></i> Редактировать</a>
                                                @if(!$convoy->visible)
                                                    <a href="{{ route('evoque.admin.convoy.toggle', $convoy->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Опубликовать</a>
                                                @else
                                                    <a href="{{ route('evoque.admin.convoy.toggle', $convoy->id) }}" class="dropdown-item"><i class="fas fa-eye-slash"></i> Спрятать</a>
                                                @endif
                                                @can('delete', \App\Convoy::class)
                                                    <a href="{{ route('evoque.admin.convoy.delete', $convoy->id) }}"
                                                       onclick="return confirm('Удалить этот конвой?')" class="dropdown-item"><i class="fas fa-trash"></i> Удалить</a>
                                                @endcan
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                                <div class="card-header row">
                                    <p class="mb-0 col-auto text-muted"><i class="fas fa-clock"></i> <b>{{ $convoy->start_time->format('H:i') }}</b></p>
                                    @if($convoy->isFulfilled())
                                        <p class="mb-0 col-auto text-muted"><i class="fas fa-server"></i> <b>{{ $convoy->server }}</b></p>
                                        <p class="mb-0 col-auto text-muted nowrap">
                                            <i class="fas fa-map-marker"></i> <b>{{ $convoy->start_city }}</b>
                                            <i class="fas fa-arrow-right mx-2"></i><b>{{ $convoy->finish_city }}</b>
                                        </p>
                                    @endif
                                    @if($convoy->dlc)
                                        <p class="mb-0 col-auto text-muted" data-toggle="popover" data-placement="bottom"
                                           data-content="Для участия требуется
                                                @foreach($convoy->dlc as $item)
                                                    {{ $item }}@if(!$loop->last), @endif
                                                @endforeach" data-trigger="hover">
                                            <i class="fas fa-puzzle-piece"></i>
                                        </p>
                                    @endif
                                    <p class="mb-0 col-auto text-muted" data-toggle="popover" data-placement="bottom"
                                       data-content="Ведущий" data-trigger="hover">
                                        <i class="fas fa-bookmark"></i>
                                        <b>
                                            @if($convoy->leadMember && $convoy->leadMember->user->vk)
                                                <a href="{{ $convoy->leadMember->user->vk }}" target="_blank" class="text-muted">{{ $convoy->lead }}</a>
                                            @else
                                                {{ $convoy->lead }}
                                            @endif
                                        </b>
                                    </p>
                                </div>
                                @if($convoy->isFulfilled())
                                    <div class="collapse" id="convoy-{{ $convoy->id }}-info" aria-labelledby="convoy-{{ $convoy->id }}-header">
                                        <div class="card-body row @if($convoy->isUpcoming())upcoming @endif">
                                            <div class="col-auto">
                                                <p class="text-muted mb-0">Старт:</p>
                                                <h4>{{ $convoy->start_city }}</h4>
                                                <h6>{{ $convoy->start_company }}</h6>
                                                <p class="mb-0 text-muted">Перерыв:</p>
                                                <h4>{{ $convoy->rest_city }}</h4>
                                                <h6>{{ $convoy->rest_company }}</h6>
                                                <p class="mb-0 text-muted">Финиш:</p>
                                                <h4>{{ $convoy->finish_city }}</h4>
                                                <h6>{{ $convoy->finish_company }}</h6>
                                                <p class="mt-4 mb-0 text-muted">Сбор:</p>
                                                <h5>{{ $convoy->start_time->subMinutes(30)->format('H:i') }} по МСК</h5>
                                                <p class="text-muted mb-0">Выезд:</p>
                                                <h5>{{ $convoy->start_time->format('H:i  ') }} по МСК</h5>
                                                <p class="text-muted mb-0">Связь {{ $convoy->communication }}:</p>
                                                <h5><a href="{{ $convoy->getCommunicationLink() }}" target="_blank">{{ $convoy->communication_link }}</a></h5>
                                                @if($convoy->communication_channel)
                                                    <p class="text-muted mb-0">Канал на сервере:</p>
                                                    <h5>{{ $convoy->communication_channel }}</h5>
                                                @endif
                                            </div>
                                            <div class="col convoy-info">
                                                <p class="text-muted mb-0">Тягач:</p>
                                                <h6>{{ $convoy->truck ?? 'Любой' }}</h6>
                                                @if($convoy->truck_image)
                                                    <a href="/images/convoys/{{ $convoy->truck_image }}" target="_blank">
                                                        <img src="/images/convoys/{{ $convoy->truck_image }}" alt="{{ $convoy->truck }}" class="text-shadow-m w-100">
                                                    </a>
                                                @endif
                                                @if($convoy->truck_tuning)
                                                    <p class="text-muted mb-0">Тюнинг:</p>
                                                    <h6>{{ $convoy->truck_tuning }}</h6>
                                                @endif
                                                @if($convoy->truck_paint)
                                                    <p class="text-muted mb-0">Окрас:</p>
                                                    <h6>{{ $convoy->truck_paint }}</h6>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <p class="text-muted mb-0">Прицеп:</p>
                                                <h6>{{ $convoy->trailer ?? 'Любой' }}</h6>
                                                @if($convoy->trailer_image)
                                                    <a href="/images/convoys/{{ $convoy->trailer_image }}" target="_blank">
                                                        <img src="/images/convoys/{{ $convoy->trailer_image }}" alt="{{ $convoy->trailer }}" class="text-shadow-m w-100">
                                                    </a>
                                                @endif
                                                @if($convoy->trailer_tuning)
                                                    <p class="text-muted mb-0">Тюнинг:</p>
                                                    <h6>{{ $convoy->trailer_tuning }}</h6>
                                                @endif
                                                @if($convoy->trailer_paint)
                                                    <p class="text-muted mb-0">Окрас:</p>
                                                    <h6>{{ $convoy->trailer_paint }}</h6>
                                                @endif
                                                @if($convoy->cargo)
                                                    <p class="text-muted mb-0">Груз:</p>
                                                    <h6>{{ $convoy->cargo }}</h6>
                                                @endif
                                                @if($convoy->alt_trailer)
                                                    <p class="mt-4 text-muted mb-0">Прицеп без ДЛС:</p>
                                                    <h6>{{ $convoy->alt_trailer }}</h6>
                                                    @if($convoy->alt_trailer_image)
                                                        <a href="/images/convoys/{{ $convoy->alt_trailer_image }}" target="_blank">
                                                            <img src="/images/convoys/{{ $convoy->alt_trailer_image }}" alt="{{ $convoy->alt_trailer }}" class="text-shadow-m w-100">
                                                        </a>
                                                    @endif
                                                    @if($convoy->alt_trailer_tuning)
                                                        <p class="text-muted mb-0">Тюнинг:</p>
                                                        <h6>{{ $convoy->alt_trailer_tuning }}</h6>
                                                    @endif
                                                    @if($convoy->alt_trailer_paint)
                                                        <p class="text-muted mb-0">Окрас:</p>
                                                        <h6>{{ $convoy->alt_trailer_paint }}</h6>
                                                    @endif
                                                    @if($convoy->alt_cargo)
                                                        <p class="text-muted mb-0">Груз:</p>
                                                        <h6>{{ $convoy->alt_cargo }}</h6>
                                                    @endif
                                                @endif
                                            </div>
                                            @if($convoy->route)
                                                <div class="col" style="flex-grow: 2">
                                                    <h4 class="text-center">Маршрут конвоя</h4>
                                                    <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs" data-width="99%">
                                                        @foreach($convoy->route as $item)
                                                            <img src="/images/convoys/{{ $item }}">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            {{ $paginator->onEachSide(1)->links('layout.pagination') }}
        </div>
    </div>
@endsection
