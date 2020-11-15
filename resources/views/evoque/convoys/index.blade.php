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
        <div class="convoys mt-3 mb-5">
            @foreach($convoys as $day => $convoys_per_day)
                <hr class="m-auto w-50 border-primary">
                <h2 class="text-primary text-center col-12 pt-5">{{ $day }}</h2>
                <div class="row justify-content-center">
                    @foreach($convoys_per_day as $convoy)
                        <div class="card-wrapper col" style="max-width: 50%">
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
                                <p class="text-muted card-header row mx-0 justify-content-between">
                                    <span class="">{{ $convoy->getType() }}</span>
                                    @if($convoy->bookedBy)
                                        <span class="text-muted">Бронь от {{ $convoy->bookedBy->nickname }}</span>
                                    @endif
                                </p>
                                <div class="card-body">
                                    <h5 class="mb-0">{{ $convoy->start_time->isoFormat('dddd') }}</h5>
                                    <h4 class="convoy-date mb-0">{{ $convoy->start_time->isoFormat('LL') }}</h4>
                                    <h4 class="convoy-date">{{ $convoy->start_time->format('H:i') }}</h4>
                                    <p class="card-text">
                                        Ведущий: <b>{{ $convoy->lead }}</b>
                                        @if($convoy->leadMember && $convoy->leadMember->user->vk)
                                            <a href="{{ $convoy->leadMember->user->vk }}" target="_blank"><i class="fab fa-vk"></i></a>
                                        @endif
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
            @endforeach
        </div>
    </div>
@endsection
