@extends('evoque.layout.index')

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        @cannot('manage_members')
            <h2 class="pt-3 text-primary">Мои заявки</h2>
        @else
            <h2 class="pt-3 text-primary">Все заявки</h2>
        @endcan
        <div class="applications pb-5">
            @foreach($applications as $application)
                <div class="m-3 card card-dark text-shadow-m @if($application->status == 0) border-primary new @endif">
                    <h5 class="card-header">Заявка на вступление</h5>
                    <div class="card-body">
                        <h4 class="card-title">{{ $application->name }}</h4>
                        <p>
                            <a class="mr-3" href="{{ $application->vk_link }}" target="_blank"><i class="fab fa-vk"></i></a>
                            <a class="mr-3" href="{{ $application->steam_link }}" target="_blank"><i class="fab fa-steam"></i></a>
                            <a class="mr-3" href="{{ $application->tmp_link }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                        </p>
                        <p>
                            <i class="mr-3 fas fa-microphone @if($application->have_mic)active @endif"></i>
                            <i class="mr-3 fab fa-teamspeak @if($application->have_ts3)active @endif"></i>
                            <i class="mr-3 fas fa-truck-pickup @if($application->have_ats)active @endif"></i>
                        </p>
                        <p class="card-text">Ник в игре: <b>{{ $application->nickname }}</b><br>
                            Возраст: <b>{{ $application->age }} лет</b><br>
                            Часов в ETS2: <b>{{ $application->hours_played }} часов</b><br>
                            Откуда узнал: <br><b>{!! nl2br($application->referral) !!}</b></p>
                        <span class="text-muted">{{ $application->created_at->isoFormat('LLL') }}</span>
                    </div>
                    <div class="card-actions">
                        @if($application->status == 0)
                            <a href="{{ route('evoque.admin.applications.accept.recruitment', $application->id) }}" class="btn btn-outline-primary"
                                onclick="return confirm('Принять эту заявку?')">Принять</a>
                        @endif
                        <a href="{{ route('evoque.admin.applications.delete.recruitment', $application->id) }}" class="btn btn-outline-danger"
                           onclick="return confirm('Удалить эту заявку?')">Удалить</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
