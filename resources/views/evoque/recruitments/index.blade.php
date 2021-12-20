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
                    <div class="m-3 card card-dark text-shadow-m @if($application->status == 0) border-primary new @elseif($application->status == 1) border-success @elseif($application->status == 2) border-danger @endif">
                        <div class="card-header row mx-0 pr-2">
                            <div class="col px-0 app-title">
                                <h5 class="mb-0">
                                    @if($application->status == '1')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($application->status == '2')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                    @can('view', $application)
                                        <a href="{{ route('evoque.recruitments', $application->id) }}">{{ $application->name }}</a>
                                    @else
                                        {{ $application->name }}
                                    @endcan
                                </h5>
                            </div>
                            @if(count($application->comments) > 0)
                                <div class="comments-count col-auto text-muted">
                                    <i class="fas fa-comment-dots"></i> {{ count($application->comments) }}
                                </div>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->can('claim', $application) ||
                                    \Illuminate\Support\Facades\Auth::user()->can('delete', \App\Recruitment::class))
                                <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                    <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                        @can('claim', $application)
                                            <a href="{{ route('evoque.recruitments', $application->id) }}" class="dropdown-item"><i class="fas fa-eye"></i> Смотреть</a>
                                        @endcan
                                        @can('delete', \App\Recruitment::class)
                                            <a href="{{ route('evoque.recruitments.delete', $application->id) }}"
                                               class="dropdown-item" onclick="return confirm('Удалить эту заявку?')"><i class="fas fa-trash"></i> Удалить</a>
                                        @endcan
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <p>
                                <a class="mr-3" href="{{ $application->vk_link }}" target="_blank"><i class="fab fa-vk"></i></a>
                                <a class="mr-3" href="{{ $application->steam_link }}" target="_blank"><i class="fab fa-steam"></i></a>
                                <a class="mr-3" href="{{ $application->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                            </p>
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
                                    <span class="text-success">{{ $application->discord_name }}</span>
                                @elseif($application->have_ts3)
                                    <span class="text-success">Есть</span>
                                @else
                                    <span class="text-danger">Нет</span>
                                @endif
                            </p>
                            @if($application->have_mic)
                                <p class="mb-0">Микрофон:
                                    @if($application->have_mic)
                                        <span class="text-success">Есть</span>
                                    @else
                                        <span class="text-danger">Нет</span>
                                    @endif
                                </p>
                            @endif
                            <p>Наличие ATS:
                                @if($application->have_ats)
                                    <span class="text-success">Есть</span>
                                @else
                                    <span class="text-danger">Нет</span>
                                @endif
                            </p>
                            @if(isset($application->referral))
                                <p class="referral mb-0">Откуда узнал: <br><b>{!! nl2br($application->referral) !!}</b></p>
                            @endif
                        </div>
                        <div class="card-footer text-muted">
                            {{ $application->created_at->isoFormat('LLL') }}
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
