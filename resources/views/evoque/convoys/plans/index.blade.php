@extends('layout.index')

@section('title')
    Планы по конвоям | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

<div class="container plans py-5">
    @include('layout.alert')
    <h1 class="mt-3 text-primary text-center">Планы по конвоям</h1>
    <section class="pb-5 pt-3 m-auto text-center">
        <hr class="m-auto w-25 border-primary">
        <p class="mt-5">Тут находится вся информация о будущих мероприятиях в компании.<br>
            Подробности этих мероприятий будут выкладываться примерно за сутки до начала мероприятия.</p>
        <p class="mb-5">Если вы хотите провести конвой выберите подходящий (не занятый) день и забронируйте конвой - логист свяжется с вами обговорить детали.<br>
            Проведенный конвой даёт вам 1 Эвик, Эвики можно обменять на различные DLC и игры (подробности в <a href="https://evoque.team/kb" target="_blank">Базе знаний</a>)</p>
        <hr class="m-auto w-25 border-primary">
    </section>
    <div class="table-responsive">
        <table class="table table-dark table-hover text-center">
            <thead>
            <tr>
                <th class="text-right">День</th>
                <th>Дата</th>
                <th class="text-left">Конвои</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($days as $date => $day)
                <tr @if($loop->iteration === 1) class="table-active" @endif>
                    <td class="text-right">{{ $day['date']->isoFormat('dddd') }}</td>
                    <td>{{ $date }}</td>
                    <td class="w-50 text-left">
                        @if(count($day['convoys']) > 0)
                            @foreach($day['convoys'] as $convoy)
                                <p class="mb-0"><b>{{ $convoy->start_time->format('H:i') }}</b> - {{ $convoy->title }} @if($convoy->lead !== 'На месте разберёмся') (ведёт <b>{{ $convoy->lead }}</b>) @endif</p>
                            @endforeach
                        @else
                            <p class="mb-0">Свободно</p>
                        @endif
                    </td>
                    <td>
                        @can('manage_convoys')
                            <button data-toggle="modal"
                                    data-target="#book-modal"
                                    class="book-convoy btn btn-outline-warning btn-sm"
                                    data-date="{{ $day['date']->format('d.m.Y') }}"
                                    @if($day['allowedToBook'] === 0 || $loop->iteration === 1) disabled @endif>
                                Забронировать конвой
                            </button>
                        @else
                            <a @if($day['allowedToBook'] > 0 && $loop->iteration !== 1)
                               href="{{ route('evoque.convoys.plans.book', $loop->index) }}"
                               @endif
                               class="book-convoy btn btn-outline-warning btn-sm
                                @if($day['allowedToBook'] === 0 || $loop->iteration === 1) disabled @endif">
                                Забронировать конвой
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <section class="col-12 convoy-note pb-5 pt-5 m-auto">
        <hr class="m-auto">
        <blockquote class="blockquote text-center mb-4 mt-4">
            <h5 class="mb-0">ETS2:</h5>
            <ol class="text-left ml-5 mr-1 px-md-5 w-75 m-auto">
                <li>Сервера Симуляции - протяженность конвоя не менее 2000 км/конвой.</li>
                <li>Сервера аркады - протяженность конвоя не менее 2500 км/конвой.</li>
                <li>Тяжелые грузы - не менее 1500 км.</li>
                <li>Легковушки и головастики: Симуляция - не менее 2200 км, аркада - не менее 2700 км.</li>
                <li>Максимальная скорость ведущего: симуляция - 100 км/ч, аркада - 150 км/ч.</li>
            </ol>
        </blockquote>
        <blockquote class="blockquote text-center mb-4 mt-5">
            <h5 class="mb-0">ATS:</h5>
            <ol class="text-left ml-5 mr-1 px-md-5 w-75 m-auto">
                <li>Планка конвоя - протяженность конвоя не менее 2100 км/конвой.</li>
                <li>Тяжелые грузы - не менее 1500 км.</li>
                <li>Легковушки и головастики: не менее 2300 км/конвой.</li>
                <li>Максимальная скорость ведущего - 115 км/ч.</li>
            </ol>
        </blockquote>
        <hr class="m-auto">
    </section>
</div>

@can('manage_convoys')
    <!-- Book modal -->
    <div class="modal fade" id="book-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content modal-content-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Бронирование конвоя</h5>
                    <button type="button" class="close text-shadow" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nickname">Название</label>
                            <input type="text" class="form-control" id="title" name="title" value="Закрытый конвой" @cannot('manage_convoys')readonly @endcan required>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="date">Дата выезда</label>
                                <input type="text" class="form-control" id="date" name="date" autocomplete="off" readonly>
                            </div>
                            <div class="form-group col">
                                <label for="datetimepicker">Время выезда</label>
                                <input type="text" class="form-control" id="time" name="time" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lead">Ведущий</label>
                            <select class="form-control" id="lead" name="lead">
                                <option value="На месте разберёмся">На месте разберёмся</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->nickname }}">{{ $member->nickname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-info mx-1" type="submit"><i class="fas fa-save"></i> Забронировать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#time').datetimepicker({
            format: 'H:i',
            step: 30,
            theme: 'dark',
            datepicker: false,
            defaultTime: '19:30',
            scrollInput: false
        });
    </script>
@endcan

@endsection
