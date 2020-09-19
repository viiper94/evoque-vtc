@extends('evoque.layout.index')

@section('content')
    <div class="container pt-5 members-table">
        @include('layout.alert')
        @can('manage_convoys')
            <a href="{{ route('evoque.admin.convoy.add') }}" class="btn btn-outline-warning ml-3 mt-3">Создать конвой</a>
        @endcan
        <div class="convoys mt-5 mb-5">
            @foreach($convoys as $convoy)
                <div class="m-3 card card-dark text-shadow-m @if($convoy->public == 1 && $convoy->isUpcoming())border-primary @endif @if($convoy->isUpcoming())upcoming @endif">
                    <h5 class="card-header">{{ $convoy->title }}</h5>
                    <div class="card-body">
                        <h4 class="card-title">{{ $convoy->start_time->isoFormat('LLL') }}</h4>
                        <p class="card-text">
                            Старт: <b>{{ $convoy->start }}</b><br>
                            Финиш: <b>{{ $convoy->finish }}</b><br>
                            Сервер: <b>{{ $convoy->server }}</b><br>
                            Связь: <b>{{ $convoy->communication }}</b><br>
                            <a href="{{ $convoy->route }}" target="_blank">Маршрут</a>
                        </p>
                    </div>
                    @can('manage_convoys')
                        <div class="card-actions">
                            @if($convoy->public)
                                <a href="{{ route('convoys') }}" class="btn btn-outline-success" target="_blank">Смотреть</a>
                            @endif
                            <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-outline-warning">Редактировать</a>
                            <a href="{{ route('evoque.admin.convoy.delete', $convoy->id) }}" class="btn btn-outline-danger"
                               onclick="return confirm('Удалить этот конвой?')">Удалить</a>
                        </div>
                    @endcan
                </div>
            @endforeach
        </div>
    </div>
@endsection
