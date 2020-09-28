@extends('layout.index')

@section('title')
        Все конвои | @lang('general.vtc_evoque')
@endsection

@section('content')
    <div class="container pt-5 members-table">
        @include('layout.alert')
        <h1 class="text-primary ml-3 text-center">Все конвои</h1>
        <div class="row justify-content-center">
            <a href="{{ route('evoque.admin.convoy.add') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"><i class="fas fa-plus"></i> Создать конвой</a>
        </div>
        <div class="convoys mt-3 mb-5 row justify-content-around">
            @foreach($convoys as $convoy)
                <div class="card card-dark text-shadow-m col-md-5 m-3 @if($convoy->public == 1 && $convoy->isUpcoming())border-primary @endif @if($convoy->isUpcoming())upcoming @endif">
                    <h5 class="card-header">{{ $convoy->title }}</h5>
                    <div class="card-body">
                        <h5>{{ $convoy->start_time->isoFormat('dddd') }}</h5>
                        <h4 class="convoy-date">{{ $convoy->start_time->isoFormat('LLL') }}</h4>
                        <p class="card-text">
                            Старт: <b>{{ $convoy->start }}</b><br>
                            Финиш: <b>{{ $convoy->finish }}</b><br>
                            Сервер: <b>{{ $convoy->server }}</b><br>
                            Связь: <b><a href="{{ $convoy->getCommunicationLink() }}" target="_blank">{{ $convoy->communication }}</a></b><br>
                            <a href="{{ $convoy->route }}" target="_blank">Маршрут</a>
                        </p>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('evoque.admin.convoy.edit', $convoy->id) }}" class="btn btn-outline-warning"><i class="fas fa-edit"></i> Редактировать</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
