@extends('layout.index')

@section('title')
        Все конвои | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/fotorama-4.6.4/fotorama.css">
    <script src="/js/fotorama-4.6.4/fotorama.js"></script>
@endsection

@section('content')
    <div class="container pt-5 private-convoys">
        @include('layout.alert')
        @can('update', \App\Convoy::class)
            <h1 class="text-primary text-center">Все конвои</h1>
            <h5 class="text-center">
                <a href="{{ route('evoque.admin.convoy.add') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Новый конвой
                </a>
                @if(!$all)
                    <a href="{{ route('convoys.private', 'all') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus"></i> Будущие регламенты
                    </a>
                @else
                    <a href="{{ route('convoys.private') }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-minus"></i> Текущие регламенты
                    </a>
                @endif
            </h5>
        @else
            <h1 class="text-primary text-center">Регламенты конвоев</h1>
        @endcan

        @foreach($convoys as $formatted_day => $day_convoys)
            @php $day = \Carbon\Carbon::parse($formatted_day); @endphp
            <div class="row day my-5 mx-0 flex-column flex-lg-row">

                <div class="date">
                    <h1 class="text-center text-lg-right @if($day->isToday()) text-primary @elseif($day->isPast())past @endif">
                        {{ $day->format('d.m').' '.$day->isoFormat('dd') }}
                    </h1>
                </div>
                <div class="day-convoys col row @if($day->isToday()) text-primary @elseif($day->isPast())past @endif">

                    @foreach($day_convoys as $convoy)
                        <div class="card card-dark text-shadow-m col px-0 my-1 text-center text-md-left
                                @if($convoy->start_time->addMinutes(60)->isPast())
                                    past
                                @else
                                    @if($convoy->public)border-primary
                                    @elseif(\Illuminate\Support\Facades\Auth::user()->can('update', \App\Convoy::class) && !$convoy->isFulfilled())border-danger
                                    @elseif($convoy->booking && !$convoy->visible)border-info @endif
                                @endif">

                            <div class="card-header row mx-0" id="convoy-{{ $convoy->id }}-header" data-toggle="collapse" data-target="#convoy-{{ $convoy->id }}"
                                 aria-expanded="false" aria-controls="{{ $convoy->id }}">
                                <h5 class="text-center col">
                                    {{ $convoy->title }}
                                </h5>
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
                            <div class="card-header convoy-main-info justify-content-center d-none d-xl-flex d-lg-flex">
                                <p data-toggle="tooltip" title="Выезд по МСК"><i class="fas fa-clock"></i> {{ $convoy->start_time->format('H:i') }}</p>
                                @if($convoy->server)<p data-toggle="tooltip" title="Сервер"><i class="fas fa-server"></i> {{ $convoy->server }}</p> @endif
                                @if($convoy->isFulfilled())
                                    <p>
                                        <span data-toggle="tooltip" title="Старт"><i class="fas fa-map-marked-alt"></i> {{ $convoy->start_city }}</span>
                                        <span data-toggle="tooltip" title="Финиш"><i class="fas fa-arrow-right mx-2"></i> {{ $convoy->finish_city }}</span>
                                    </p>
                                @endif
                                @if($convoy->lead) <p data-toggle="tooltip" title="Ведущий"><i class="fas fa-bookmark"></i> {{ $convoy->lead }}</p> @endif
                                @if($convoy->dlc)
                                    <p data-toggle="tooltip" data-html="true"
                                       title='Для участия требуется: @foreach($convoy->dlc as $item) <span class="nowrap font-weight-bold">{{ $item }}</span> @endforeach'>
                                        <i class="fas fa-puzzle-piece"></i> DLC
                                    </p>
                                @endif
                                @if($convoy->cargoman) <p data-toggle="tooltip" title="Код CargoMan"><i class="fas fa-sign-in-alt"></i> {{ $convoy->cargoman }}</p>
                                @elseif($convoy->start_time->isFuture()) <a class="add-cargoman" data-toggle="modal" href="#cargomanModal" data-id="{{ $convoy->id }}">Добавить код CargoMan</a>
                                @endif
                            </div>

                            <div class="collapse" id="convoy-{{ $convoy->id }}" aria-labelledby="convoy-{{ $convoy->id }}-header">
                                @if($convoy->isFulfilled())
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6">
                                                <p class="mb-0 text-muted">@lang('attributes.start_city')</p>
                                                <h3>{{ $convoy->start_city }}</h3>
                                                <h5 class="mb-3">{{ $convoy->start_company }}</h5>
                                                <p class="mb-0 text-muted">@lang('attributes.rest_city')</p>
                                                <h3>{{ $convoy->rest_city }}</h3>
                                                <h5 class="mb-3">{{ $convoy->rest_company }}</h5>
                                                <p class="mb-0 text-muted">@lang('attributes.finish_city')</p>
                                                <h3>{{ $convoy->finish_city }}</h3>
                                                <h5 class="mb-3">{{ $convoy->finish_company }}</h5>
                                                <p class="mb-0 text-muted">Сбор по МСК</p>
                                                <h5 class="mb-1">{{ $convoy->start_time->subMinutes(30)->format('H:i') }}</h5>
                                                <p class="mb-0 text-muted">@lang('attributes.start_time')</p>
                                                <h5>{{ $convoy->start_time->format('H:i') }}</h5>
                                            </div>
                                            <div class="col-xl-4 col-md-6">
                                                @if($convoy->cargoman)
                                                    <p class="mb-0 text-muted">CargoMan :</p>
                                                    <h5>{{ $convoy->cargoman }}</h5>
                                                    <p class="mb-1"><a href="{{route('kb.view', 18) }}" class="text-muted">Как присоединиться?</a></p>
                                                @endif
                                                <p class="mb-0 text-muted">Сервер:</p>
                                                <h5 class="mb-1">{{ $convoy->server }}</h5>
                                                <p class="mb-0 text-muted">Ведущий:</p>
                                                <h5 class="mb-1">
                                                    @if($convoy->leadMember?->user->vk)
                                                        <a href="{{ $convoy->leadMember->user->vk }}" target="_blank">{{ $convoy->lead }}</a>
                                                    @else
                                                        {{ $convoy->lead }}
                                                    @endif
                                                </h5>
                                                <p class="mb-0 text-muted">Связь {{ $convoy->communication }}:</p>
                                                <h5 class="mb-1">
                                                    <a href="{{ $convoy->getCommunicationLink() }}" target="_blank">
                                                        {{ $convoy->communication_link }}
                                                    </a>
                                                </h5>
                                                @if($convoy->communication_channel)
                                                    <p class="mb-0 text-muted">Канал на сервере:</p>
                                                    <h5 >{{ $convoy->communication_channel }}</h5>
                                                @endif
                                            </div>
                                            @if($convoy->route)
                                                <div class="fotorama col-xl-4 col-md-12 align-self-baseline text-shadow-m" data-allowfullscreen="native" data-nav="thumbs" data-maxheight="700px">
                                                    @foreach($convoy->route as $image)
                                                        <img src="/images/convoys/{{ $image }}">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row justify-content-center mt-5">
                                            <div class="col-md-12 col-xl-6 text-center text-md-left text-xl-right row justify-content-end mx-0 flex-column-reverse flex-md-row-reverse flex-xl-row truck-info">
                                                <div class="col">
                                                    <p class="mb-0 text-muted">@lang('attributes.truck')</p>
                                                    <h5 class="mb-1">{{ $convoy->truck ?? 'Любой' }}</h5>
                                                    @if($convoy->truck_tuning)
                                                        <p class="mb-0 text-muted">Тюнинг:</p>
                                                        <h5 class="mb-1">{{ $convoy->truck_tuning }}</h5>
                                                    @endif
                                                    @if($convoy->truck_paint)
                                                        <p class="mb-0 text-muted">Окрас:</p>
                                                        <h5>{{ $convoy->truck_paint }}</h5>
                                                    @endif
                                                </div>
                                                @if($convoy->truck_image)
                                                    <div class="col truck-img">
                                                        <a href="/images/convoys/{{ $convoy->truck_image }}" target="_blank">
                                                            <img src="/images/convoys/{{ $convoy->truck_image }}" alt="{{ $convoy->truck }}" class="text-shadow-m w-100">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-12 col-xl-6 row justify-content-start mx-0 trailer-info">
                                                <div class="row mx-0 flex-column flex-md-row mt-5 mt-md-3 mt-lg-0">
                                                    @if($convoy->trailer_image)
                                                        <div class="col">
                                                            <a href="/images/convoys/{{ $convoy->trailer_image }}" target="_blank">
                                                                <img src="/images/convoys/{{ $convoy->trailer_image }}" alt="{{ $convoy->trailer }}" class="text-shadow-m w-100">
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="col">
                                                        <p class="mb-0 text-muted">@lang('attributes.trailer')</p>
                                                        <h5 class="mb-1">{{ $convoy->trailer ?? 'Любой' }}</h5>
                                                        @if($convoy->trailer_tuning)
                                                            <p class="mb-0 text-muted">Тюнинг:</p>
                                                            <h5 class="mb-1">{{ $convoy->trailer_tuning }}</h5>
                                                        @endif
                                                        @if($convoy->trailer_paint)
                                                            <p class="mb-0 text-muted">Окрас:</p>
                                                            <h5 class="mb-1">{{ $convoy->trailer_paint }}</h5>
                                                        @endif
                                                        @if($convoy->cargo)
                                                            <p class="mb-0 text-muted">Груз:</p>
                                                            <h5>{{ $convoy->cargo }}</h5>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($convoy->alt_trailer)
                                                    <div class="row mt-3 mx-0 flex-column flex-md-row mt-5 mt-md-3">
                                                        @if($convoy->alt_trailer_image)
                                                            <div class="col">
                                                                <a href="/images/convoys/{{ $convoy->alt_trailer_image }}" target="_blank">
                                                                    <img src="/images/convoys/{{ $convoy->alt_trailer_image }}" alt="{{ $convoy->alt_trailer }}" class="text-shadow-m w-100">
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <div class="col">
                                                            <p class="mb-0 text-muted">@lang('attributes.alt_trailer')</p>
                                                            <h5 class="mb-1">{{ $convoy->alt_trailer ?? 'Любой' }}</h5>
                                                            @if($convoy->alt_trailer_tuning)
                                                                <p class="mb-0 text-muted">Тюнинг:</p>
                                                                <h5 class="mb-1">{{ $convoy->alt_trailer_tuning }}</h5>
                                                            @endif
                                                            @if($convoy->alt_trailer_paint)
                                                                <p class="mb-0 text-muted">Окрас:</p>
                                                                <h5 class="mb-1">{{ $convoy->alt_trailer_paint }}</h5>
                                                            @endif
                                                            @if($convoy->alt_cargo)
                                                                <p class="mb-0 text-muted">Груз:</p>
                                                                <h5>{{ $convoy->alt_cargo }}</h5>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($convoy->comment))
                                            <div class="comment py-3 markdown-content">
                                                @markdown($convoy->comment)
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer convoy-main-info row mx-0 justify-content-center">
                                        <p><i class="fas fa-arrows-alt-h"></i> Дистанция 70-150м</p>
                                        <p><i class="fas fa-rss"></i> Канал рации №7</p>
                                        <p><i class="fas fa-sun"></i> Ближний свет фар</p>
                                        <p><i class="fab fa-discord"></i> Сбор в нашем Discord</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

        {{ $paginator->onEachSide(1)->links('layout.pagination') }}

    </div>

    <div class="modal fade" id="cargomanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px">
            <div class="modal-content modal-content-dark">
                <form action="{{ route('evoque.admin.convoy.add.cargoman') }}" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавление кода CargoMan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="text-muted ml-3">
                            <li>Ознакомьтесь со статьей - <a href="{{ route('kb.view', 17) }}" target="_blank">Как раздать груз?</a></li>
                            <li>Избегайте использования DLC при раздаче прицепа/тягача/груза.</li>
                            <li>Код будет доступен всего 30 минут с момента его создания.</li>
                        </ul>
                        <div class="form-group">
                            <input type="number" class="form-control form-control-lg" id="cargoman" name="cargoman" placeholder="Вставьте код" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id" name="id">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
