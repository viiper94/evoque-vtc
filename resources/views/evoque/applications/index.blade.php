@extends('layout.index')

@section('title')
    Заявки | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        @can('create', \App\Application::class)
            <div class="application-buttons mt-5 mb-5 row justify-content-center">
                @can('createVacation', \App\Application::class)
                    <a href="{{ route('evoque.applications.vacation') }}" class="btn btn-lg btn-outline-warning m-1">Хочу в отпуск!</a>
                @endcan
                <a href="{{ route('evoque.applications.plate') }}" class="btn btn-lg btn-outline-info m-1">Сменить номер</a>
                <a href="{{ route('evoque.applications.rp') }}" class="btn btn-lg btn-outline-success m-1">Сменить уровень в рейтинговых</a>
                <a href="{{ route('evoque.applications.nickname') }}" class="btn btn-lg btn-outline-primary m-1">Сменить никнейм</a>
                <a href="{{ route('evoque.applications.fire') }}" class="btn btn-lg btn-outline-danger m-1">Увольняюсь!</a>
            </div>
        @endcan
        @cannot('view', \App\Application::class)
            <h2 class="mt-3 text-primary text-center">Мои заявки</h2>
        @else
            <h2 class="mt-3 text-primary text-center">Все заявки сотрудников</h2>
        @endcannot
        @can('view', \App\Recruitment::class)
            <div class="row pt-3 justify-content-center">
                <a href="{{ route('evoque.applications.recruitment') }}" class="btn btn-outline-warning btn-sm">
                    Заявки на вступление
                    @can('accept', \App\Recruitment::class)
                        @if($recruitments > 0)<span class="badge badge-danger">{{ $recruitments }}</span>@endif
                    @endcan
                </a>
            </div>
        @endcan
        @if(count($apps) > 0)
            <div class="applications pt-5 pb-5 row">
                @foreach($apps as $app)
                    <div class="m-3 card card-dark text-shadow-m @if($app->status == 0) new border-primary @elseif($app->status == '1') border-success @else border-danger @endif">
                        <h5 class="card-header @if($app->status == 0) @if($app->category === 1) text-warning @elseif($app->category === 5) text-danger @endif @endif">
                            Заявка на {{ $app->getCategory() }}
                            @if($app->status == '0')
                                <span class="badge badge-warning">Рассматривается</span>
                            @elseif($app->status == '1')
                                <span class="badge badge-success">Принята</span>
                            @elseif($app->status == '2')
                                <span class="badge badge-danger">Отклонена</span>
                            @endif
                        </h5>
                        <div class="card-body">
                            <h4 class="card-title mb-0">
                                От <span class="@if($app->status == 0)text-primary @endif ">{{ !in_array($app->category, [4, 5]) ? $app->member->nickname : $app->old_nickname }}</span>
                            </h4>
                            @if($app->member && $app->category === 4)
                                <p class="text-muted">Текущий ник: <b>{{ $app->member->nickname }}</b></p>
                            @endif
                            @if($app->comment)
                                <p class="mb-0 pt-3">Комментарий от администратора: </p>
                                @markdown($app->comment)
                            @endif
                            @switch($app->category)
                                @case(1)
                                    <p class="mb-0">Отпуск до: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>{{ $app->vacation_till->isoFormat('LL') }}</h5>
                                    @break
                                @case(2)
                                    <p class="mb-0">Желаемый номер: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>EVOQUE {{ $app->new_plate_number }}
                                        <a href="https://worldoftrucks.com/api/license_plate/eut2/germany/rear/evoque%20{{ $app->new_plate_number }}" target="_blank">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    </h5>
                                @break
                                @case(3)
                                    <p class="mb-0">Новый уровень в {{ strtoupper($app->new_rp_profile[0]) }}: <b>{{ $app->new_rp_profile[1] }}</b></p>
                                    @break
                                @case(4)
                                    <p class="mb-0">Новый никнейм: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>{{ $app->new_nickname }}</h5>
                                    @break
                                @case(5) @break
                            @endswitch
                            @if($app->reason)
                                <p class="mb-0 pt-3">Причина: </p>
                                @markdown($app->reason)
                            @endif
                            <span class="text-muted">{{ $app->created_at->isoFormat('LLL') }}</span>
                        </div>
                        <div class="card-actions">
                            @can('claim', $app)
                                <a href="{{ route('evoque.applications', $app->id) }}" class="btn btn-outline-primary my-1">Смотреть</a>
                            @endcan
                            @can('update', $app)
                                <a href="{{ route('evoque.applications.edit', $app->id) }}" class="btn btn-outline-primary my-1">Редактировать</a>
                            @endcan
                            @can('delete', $app)
                                <a href="{{ route('evoque.applications.delete', $app->id) }}" class="btn btn-outline-danger my-1"
                                   onclick="return confirm('Удалить эту заявку?')">Удалить</a>
                            @endcan
                        </div>
                    </div>
                @endforeach
                {{ $apps->links('layout.pagination') }}
            </div>
        @else
            <div class="row justify-content-center pt-5 pb-5">
                <h5>Еще нет заявок</h5>
            </div>
        @endif
    </div>

@endsection
