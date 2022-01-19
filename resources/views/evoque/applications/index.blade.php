@extends('layout.index')

@section('title')
    Заявки | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/js/simplemde/dist/simplemde-dark.min.css">
    <script src="/js/simplemde/dist/simplemde.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        @can('create', \App\Application::class)
            <div class="application-buttons mt-5 mb-5 row justify-content-center">
                <button class="btn btn-lg m-1 @cannot('createVacation', \App\Application::class) btn-outline-secondary disabled @else btn-outline-warning" data-toggle="modal" data-target="#vacationModal" data-fake=" @endcannot">
                    Хочу в отпуск!
                </button>
                <button class="btn btn-lg btn-outline-success m-1" data-toggle="modal" data-target="#rpModal">Сменить уровень в рейтинговых</button>
                <button class="btn btn-lg btn-outline-info m-1" data-toggle="modal" data-target="#nicknameModal">Сменить ник</button>
                <button class="btn btn-lg btn-outline-danger m-1" data-toggle="modal" data-target="#fireModal">Увольняюсь!</button>
            </div>
        @endcan
        @cannot('viewAll', \App\Application::class)
            <h2 class="mt-3 text-primary text-center">Мои заявки</h2>
        @else
            <h2 class="mt-3 text-primary text-center">Все заявки сотрудников</h2>
        @endcannot
        @can('view', \App\Recruitment::class)
            <div class="row pt-3 justify-content-center">
                <a href="{{ route('evoque.recruitments') }}" class="btn btn-outline-warning btn-sm">
                    Заявки на вступление
                    @can('accept', \App\Recruitment::class)
                        @if($recruitments > 0)<span class="badge badge-danger">{{ $recruitments }}</span>@endif
                    @endcan
                </a>
            </div>
        @endcan
    </div>
    <div class="container-fluid">
        @if(count($apps) > 0)
            <div class="applications pt-3 pb-5 row">
                @foreach($apps as $app)
                    <div class="m-3 card card-dark text-shadow-m @if($app->status == 0) new border-primary @elseif($app->status == '1') border-success @else border-danger @endif">
                        <div class="card-header mx-0 row @if($app->status == 0) @if($app->category === 1) text-warning @elseif($app->category === 5) text-danger @endif @endif">
                            <div class="col px-0 app-title position-static">
                                <h5 class="mb-0">
                                    @if($app->status == '0')
                                        <i class="fas fa-arrow-alt-circle-up text-warning"></i>
                                    @elseif($app->status == '1')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($app->status == '2')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                    @can('view', $app)
                                        <a href="{{ route('evoque.applications', $app->id) }}" class="stretched-link">Заявка на {{ $app->getCategory() }}</a>
                                    @else
                                        Заявка на {{ $app->getCategory() }}
                                    @endcan
                                </h5>
                            </div>
                            @if(\Illuminate\Support\Facades\Auth::user()->can('claim', $app) ||
                                    \Illuminate\Support\Facades\Auth::user()->can('delete', $app))
                                <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                    <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                        @can('claim', $app)
                                            <a href="{{ route('evoque.applications', $app->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Смотреть</a>
                                        @endcan
                                        @can('delete', $app)
                                            <a href="{{ route('evoque.applications.delete', $app->id) }}"
                                               class="dropdown-item" onclick="return confirm('Удалить эту заявку?')"><i class="fas fa-trash"></i> Удалить</a>
                                        @endcan
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h4 class="card-title mb-0">
                                От <span class="@if($app->status == 0)text-primary @endif ">{{ $app->category === 4 || !$app->member ? $app->old_nickname : $app->member->nickname }}</span>
                            </h4>
                            @if($app->member && $app->category === 4)
                                <p class="text-muted">Текущий ник: <b>{{ $app->member->nickname }}</b></p>
                            @endif
                            @switch($app->category)
                                @case(1)
                                    <p class="mb-0 mt-3">Отпуск: </p>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>с {{ \Carbon\Carbon::parse($app->vacation_till['from'])->isoFormat('LL') }}</h5>
                                    <h5 @if($app->status == 0) class="text-primary" @endif>по {{ \Carbon\Carbon::parse($app->vacation_till['to'])->isoFormat('LL') }}</h5>
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
                                <div class="markdown-content referral">
                                    @markdown($app->reason)
                                </div>
                            @endif
                        </div>
                        <div class="card-footer row text-muted">
                            <span class="col">{{ $app->created_at->isoFormat('LLL') }}</span>
                            @if(count($app->comments) > 0)
                                <div class="comments-count col-auto text-muted">
                                    <i class="fas fa-comment-dots"></i> {{ count($app->comments) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $apps->links('layout.pagination') }}
        @else
            <div class="row justify-content-center pt-5 pb-5">
                <h5>Еще нет заявок</h5>
            </div>
        @endif
    </div>

    @can('create', \App\Application::class)
        @include('evoque.applications.fire')
        @include('evoque.applications.nickname')
        @include('evoque.applications.rp')
        @can('createVacation', \App\Application::class)
            @include('evoque.applications.vacation')
        @endcan
    @endcan

@endsection
