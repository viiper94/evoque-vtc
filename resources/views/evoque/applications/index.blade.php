@extends('layout.index')

@section('title')
    Заявки | @lang('general.vtc_evoque')
@endsection

@section('assets')
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
                    <div @class([
                            'm-3 application card card-dark text-shadow-m',
                            'new border-primary' => $app->status === 0,
                            'border-success' => $app->status === 1,
                            'border-danger' => $app->status === 2,
                         ]) data-id="{{ $app->id }}">
                        <div @class([
                                 'card-header mx-0 row',
                                 'text-warning' => $app->status === 0 && $app->category === 1,
                                 'text-danger' => $app->status === 0 && $app->category === 5,
                             ])>
                            <div class="col px-0 app-title position-static">
                                <h5 class="mb-0">
                                    @switch($app->status)
                                        @case(0) <i class="fas fa-arrow-alt-circle-up text-warning"></i> @break
                                        @case(1) <i class="fas fa-check-circle text-success"></i> @break
                                        @case(2) <i class="fas fa-times-circle text-danger"></i> @break
                                    @endswitch
                                    Заявка на {{ $app->getCategory() }}
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title mb-0">
                                От <span @class(['text-primary' => $app->status === 0])">{{ $app->category === 4 || !$app->member ? $app->old_nickname : $app->member->nickname }}</span>
                            </h4>
                            @if($app->member && $app->category === 4)
                                <p class="text-muted">Текущий ник: <b>{{ $app->member->nickname }}</b></p>
                            @endif
                            @switch($app->category)
                                @case(1)
                                    <p class="mb-0 mt-3">Отпуск: </p>
                                    <h5 @class(['text-primary' => $app->status === 0])>с {{ \Carbon\Carbon::parse($app->vacation_till['from'])->isoFormat('LL') }}</h5>
                                    <h5 @class(['text-primary' => $app->status === 0])>по {{ \Carbon\Carbon::parse($app->vacation_till['to'])->isoFormat('LL') }}</h5>
                                    @break
                                @case(3)
                                    <p class="mb-0">Новый уровень в {{ strtoupper($app->new_rp_profile[0]) }}
                                        @if(isset($app->new_rp_profile[2]))
                                            (@lang('general.'.$app->new_rp_profile[2]))
                                        @endif
                                        : <b>{{ $app->new_rp_profile[1] }}</b>
                                    </p>
                                    @break
                                @case(4)
                                    <p class="mb-0">Новый никнейм: </p>
                                    <h5 @class(['text-primary' => $app->status === 0])>{{ $app->new_nickname }}</h5>
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
                            @if($app->comments_count > 0)
                                <div class="comments-count col-auto text-muted">
                                    <i class="fas fa-comment-dots"></i> {{ $app->comments_count }}
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

    @can('view', $app)
        <!-- Application Modal -->
        <div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="appModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-content-dark text-shadow-m"></div>
            </div>
        </div>
    @endcan

@endsection
