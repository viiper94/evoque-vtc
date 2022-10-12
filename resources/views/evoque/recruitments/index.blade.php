@extends('layout.index')

@section('title')
    Заявки | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 text-primary text-center">Заявки на вступление</h2>
        <div class="row pt-3 justify-content-center">
            <a href="{{ route('evoque.applications') }}" class="btn btn-outline-warning btn-sm">
                Заявки сотрудников
                @can('viewAll', \App\Application::class)
                    @if($apps > 0)<span class="badge badge-danger">{{ $apps }}</span>@endif
                @endcan
            </a>
        </div>
    </div>
    <div class="container-fluid">
        @if(count($applications) > 0)
            <div class="applications pt-3 pb-5 row">
                @foreach($applications as $application)
                    <div @class(['m-3 card card-dark text-shadow-m',
                            'new' => !$application->isClosed(),
                            \App\Enums\Status::from($application->status)->colorClass('border-')
                        ])>
                        <div class="card-header row mx-0 pr-2">
                            <div class="col px-0 app-title position-static">
                                <h5 class="mb-0">
                                    @if($application->status === 1)
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($application->status === 2)
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                    @can('view', $application)
                                        <a href="{{ route('evoque.recruitments', $application->id) }}" class="stretched-link">{{ $application->name }}</a>
                                    @else
                                        {{ $application->name }}
                                    @endcan
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Ник в игре: <b>{{ $application->nickname }}</b><br>
                                Возраст: <b>{{ $application->age }} {{ trans_choice('год|года|лет', $application->age) }}</b><br>
                                Часов наиграно: <b>{{ $application->hours_played }} {{ trans_choice('час|часа|часов', $application->hours_played) }}</b><br>
                                @if($application->tmp_join_date)
                                    В TruckersMP с: <b>{{ $application->tmp_join_date->isoFormat('LL') }}</b>
                                @endif
                            </p>
                            <p class="mb-0">Discord:
                                @if($application->discord_name)
                                    <b @class(['text-success' => !$application->isClosed()])>{{ $application->discord_name }}</b>
                                @elseif($application->have_ts3)
                                    <b @class(['text-success' => !$application->isClosed()])>Есть</b>
                                @else
                                    <b @class(['text-danger' => !$application->isClosed()])>Нет</b>
                                @endif
                            </p>
                            <p>Наличие ATS:
                                @if($application->have_ats)
                                    <b @class(['text-success' => !$application->isClosed()])>Есть</b>
                                @else
                                    <b @class(['text-danger' => !$application->isClosed()])>Нет</b>
                                @endif
                            </p>
                            @if(isset($application->referral))
                                <p class="referral mb-0">Откуда узнал: <br>{!! nl2br($application->referral) !!}</p>
                            @endif
                        </div>
                        <div class="card-footer row text-muted">
                            <span class="col">{{ $application->created_at->isoFormat('LLL') }}</span>
                            @if($application->comments_count > 0)
                                <div class="comments-count col-auto text-muted">
                                    <i class="fas fa-comment-dots"></i> {{ $application->comments_count }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $applications->links('layout.pagination') }}
        @else
            <div class="row justify-content-center pt-5 pb-5">
                <h5>Еще нет заявок</h5>
            </div>
        @endif
    </div>

@endsection
