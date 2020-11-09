@extends('layout.index')

@section('title')
        Все конвои | @lang('general.vtc_evoque')
@endsection

@section('content')
    <div class="container pt-5 members-table">
        @include('layout.alert')
        <h1 class="mt-3 text-primary ml-3 text-center">Все конвои</h1>
        <div class="row justify-content-center">
            <a href="{{ route('evoque.admin.convoy.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Создать конвой</a>
        </div>
        <div class="convoys mt-3 mb-5 row justify-content-center">
            @foreach($convoys as $convoy)
                <div class="card-wrapper col-md-6">
                    <div class="card card-dark text-shadow-m m-3
                        @if($convoy->public && $convoy->isUpcoming()) border-primary
                        @elseif($convoy->booking && !$convoy->visible) border-danger @endif
                    @if($convoy->isUpcoming())upcoming @endif">
                        <h5 class="card-header">
                            {{ $convoy->title }}
                            @if(!$convoy->visible)
                                <span class="badge badge-warning">Неопубликован</span>
                            @else
                                <span class="badge badge-success">Опубликован</span>
                            @endif
                        </h5>
                        <div class="card-body">
                            @if($convoy->bookedBy)
                                <h5 class="text-info">Бронь от {{ $convoy->bookedBy->nickname }}</h5>
                            @endif
                            <h5>{{ $convoy->start_time->isoFormat('dddd') }}</h5>
                            <h4 class="convoy-date">{{ $convoy->start_time->isoFormat('LLL') }}</h4>
                            <p class="card-text">
                                @if(!($convoy->booking && !$convoy->visible))
                                    @if($convoy->start_city)
                                        Старт: <b>{{ $convoy->start_city }} {{ $convoy->start_company }}</b><br>
                                    @endif
                                    @if($convoy->finish)
                                        Финиш: <b>{{ $convoy->finish }} {{ $convoy->finish_company }}</b><br>
                                    @endif
                                    @if($convoy->server)
                                        Сервер: <b>{{ $convoy->server }}</b><br>
                                    @endif
                                    @if($convoy->communication)
                                        Связь: <b><a href="{{ $convoy->getCommunicationLink() }}" target="_blank">{{ $convoy->communication }}</a></b><br>
                                    @endif
                                @endif
                                Ведущий: <b>{{ $convoy->lead }}</b><br>
                            </p>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-outline-warning my-1"><i class="fas fa-edit"></i> Редактировать</a>
                            @if(!$convoy->visible)
                                <a href="{{ route('evoque.admin.convoy.toggle', $convoy->id) }}" class="btn btn-outline-success my-1"><i class="fas fa-eye"></i> Опубликовать</a>
                            @else
                                <a href="{{ route('evoque.admin.convoy.toggle', $convoy->id) }}" class="btn btn-outline-info my-1"><i class="fas fa-eye-slash"></i> Спрятать</a>
                            @endif
                            <a href="{{ route('evoque.admin.convoy.delete', $convoy->id) }}" class="btn btn-outline-danger my-1"
                               onclick="return confirm('Удалить этот конвой?')"><i class="fas fa-trash"></i> Удалить</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $convoys->links('layout.pagination') }}
    </div>
@endsection
