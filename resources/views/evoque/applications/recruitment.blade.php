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
                @if($apps > 0)<span class="badge badge-danger">{{ $apps }}</span>@endif
            </a>
        </div>
        @if(count($applications) > 0)
            <div class="applications pt-5 pb-5 row">
                @foreach($applications as $application)
                    <div class="m-3 card card-dark text-shadow-m @if($application->status == 0) border-primary new @endif">
                        <h4 class="card-header">{{ $application->name }}</h4>
                        <div class="card-body">
                            <p>
                                <a class="mr-3" href="{{ $application->vk_link }}" target="_blank"><i class="fab fa-vk"></i></a>
                                <a class="mr-3" href="{{ $application->steam_link }}" target="_blank"><i class="fab fa-steam"></i></a>
                                <a class="mr-3" href="{{ $application->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                            </p>
                            <p class="card-text">Ник в игре: <b>{{ $application->nickname }}</b><br>
                                Возраст: <b>{{ $application->age }} лет</b><br>
                                Часов в ETS2: <b>{{ $application->hours_played }} {{ trans_choice('час|часа|часов', $application->hours_played) }}</b></p>
                            <p class="mb-0">Микрофон: @if($application->have_mic) <span class="text-success">Есть</span> @else <span class="text-danger">Нету</span> @endif</p>
                            <p class="mb-0">Discord: @if($application->have_ts3) <span class="text-success">Есть</span> @else <span class="text-danger">Нету</span> @endif</p>
                            <p>Наличие ATS: @if($application->have_ats) <span class="text-success">Есть</span> @else <span class="text-danger">Нету</span> @endif</p>
                            <p>Откуда узнал: <br><b>{!! nl2br($application->referral) !!}</b></p>
                            <span class="text-muted">{{ $application->created_at->isoFormat('LLL') }}</span>
                        </div>
                        <div class="card-actions">
                            @if($application->status == 0)
                                <a href="{{ route('evoque.applications.accept.recruitment', $application->id) }}" class="btn btn-outline-primary"
                                    onclick="return confirm('Принять эту заявку?')">Принять</a>
                            @endif
                            <a href="{{ route('evoque.applications.delete.recruitment', $application->id) }}" class="btn btn-outline-danger"
                               onclick="return confirm('Удалить эту заявку?')">Удалить</a>
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
