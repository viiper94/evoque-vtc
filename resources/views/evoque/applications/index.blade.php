@extends('layout.index')

@section('title')
    Заявки | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <div class="application-buttons mt-5 mb-5 row justify-content-center">
            <a href="{{ route('evoque.applications.vacation') }}" class="btn btn-lg btn-outline-warning m-1">Хочу в отпуск!</a>
            <a href="{{ route('evoque.applications.plate') }}" class="btn btn-lg btn-outline-info m-1">Сменить номер</a>
            <a href="{{ route('evoque.applications.rp') }}" class="btn btn-lg btn-outline-success m-1">Сбросить статистику в рейтинговых</a>
            <a href="{{ route('evoque.applications.nickname') }}" class="btn btn-lg btn-outline-primary m-1">Сменить никнейм</a>
            <a href="{{ route('evoque.applications.fire') }}" class="btn btn-lg btn-outline-danger m-1">Увольняюсь!</a>
        </div>
        @cannot('manage_members')
            <h2 class="mt-3 text-primary text-center">Мои заявки</h2>
        @else
            <h2 class="mt-3 text-primary text-center">Все заявки сотрудников</h2>
            <div class="row pt-3 justify-content-center">
                <a href="{{ route('evoque.applications.recruitment') }}" class="btn btn-outline-warning btn-sm">
                    Заявки на вступление
                    @if($recruitments > 0)<span class="badge badge-danger">{{ $recruitments }}</span>@endif
                </a>
            </div>
        @endcan
        @if(count($apps) > 0)
            <div class="applications pt-5 pb-5 row">
                @foreach($apps as $app)
                    <div class="m-3 card card-dark text-shadow-m @if($app->status == 0) border-primary new @endif">
                        <h5 class="card-header @if($app->status == 0) @if($app->category === 1) text-warning @elseif($app->category === 5) text-danger @endif @endif">Заявка на {{ $app->getCategory() }}</h5>
                        <div class="card-body">
                            <h4 class="card-title mb-0">От <span class="@if($app->status == 0)text-primary @endif ">{{ $app->old_nickname }}</span></h4>
                            @if($app->member)
                                <p>Текущий ник: <b>{{ $app->member->nickname }}</b></p>
                            @endif
                            @switch($app->category)
                                @case(1)
                                    <p class="mb-0">Отпуск до: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>{{ $app->vacation_till->isoFormat('LL') }}</h5>
                                    @break
                                @case(2)
                                    <p class="mb-0">Желаемый номер: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>{{ $app->new_plate_number }}
                                        <a href="https://worldoftrucks.com/api/license_plate/eut2/germany/rear/{{ str_replace(' ', '%20', $app->new_plate_number) }}" target="_blank">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    </h5>
                                @break
                                @case(3) @break
                                @case(4)
                                    <p class="mb-0">Новый никнейм: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>{{ $app->new_nickname }}</h5>
                                    @break
                                @case(5) @break
                            @endswitch
                            @if($app->reason)
                                <p class="mb-0 pt-3">Причина: </p>
                                <h5>{!! nl2br($app->reason) !!}</h5>
                            @endif
                            <span class="text-muted">{{ $app->created_at->isoFormat('LLL') }}</span>
                        </div>
                        <div class="card-actions">
                            @can('manage_members')
                                @if($app->status == 0)
                                    <a href="{{ route('evoque.applications.accept', $app->id) }}" class="btn btn-outline-primary"
                                        onclick="return confirm('Принять эту заявку?')">Принять</a>
                                @endif
                            @endcan
                            @if(($app->member_id === \Illuminate\Support\Facades\Auth::user()->member->id && $app->status == 0) || \Illuminate\Support\Facades\Gate::allows('manage_members'))
                                <a href="{{ route('evoque.applications.delete', $app->id) }}" class="btn btn-outline-danger"
                                   onclick="return confirm('Удалить эту заявку?')">Удалить</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center pt-5 pb-5">
                <h5>Еще нет заявок</h5>
            </div>
        @endif
    </div>

@endsection
