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
                <div class="row flex-column flex-md-row mx-0">
                    <h1 class="text-primary text-md-right text-center col-md-auto mt-md-5 mt-3 p-0 convoys-day">{{ $day }}</h1>
                    <div class="row col-md convoys-list m-0 p-0 my-0 my-md-3 mx-md-5 px-md-0 accordion" id="convoysAccordion-{{ $loop->index }}">
                        @foreach($convoys_per_day as $convoy)
                            <div class="card card-dark text-shadow-m my-md-2 ml-md-5 col-12 px-md-0
                                @if($convoy->public && $convoy->isUpcoming()) border-primary
                                @elseif(($convoy->booking && !$convoy->visible) || !$convoy->start_city) border-danger @endif
                                @if($convoy->isUpcoming())upcoming @endif">
                                <div class="card-header row mb-0 mx-0" id="convoy-{{ $convoy->id }}-header"
                                     data-toggle="collapse" data-target="#convoy-{{ $convoy->id }}-info"
                                     aria-expanded="false" aria-controls="convoy-{{ $convoy->id }}-info">
                                    <div class="col px-0 app-title">
                                        <h5 class="m-auto text-center">{{ $convoy->start_time->format('H:i') }} - {{ $convoy->title }}</h5>
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
                                <div class="card-header">
                                        <span class="text-muted">
                                            @if(!$convoy->visible)
                                                <i class="fas fa-eye-slash text-warning"></i>
                                            @else
                                                <i class="fas fa-eye text-success"></i>
                                            @endif
                                            {{ $convoy->getType() }}
                                        </span>
                                    <p class="card-text">
                                        Ведущий: <b>{{ $convoy->lead }}</b>
                                        @if($convoy->leadMember && $convoy->leadMember->user->vk)
                                            <a href="{{ $convoy->leadMember->user->vk }}" target="_blank"><i class="fab fa-vk"></i></a>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-body collapse" id="convoy-{{ $convoy->id }}-info" aria-labelledby="convoy-{{ $convoy->id }}-header" data-parent="#convoysAccordion-{{ $loop->parent->index }}">
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="clearfix"></div>
            @endforeach
            {{ $paginator->onEachSide(1)->links('layout.pagination') }}
        </div>
    </div>
@endsection
