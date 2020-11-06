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
            @foreach($days as $day)
                <tr @if($loop->iteration === 1) class="table-active" @endif>
                    <td class="text-right">{{ $day['date']->isoFormat('dddd') }}</td>
                    <td>{{ $day['date']->format('d.m') }}</td>
                    <td class="w-50 text-left">
                        @if(count($day['convoys']) > 0)
                            @foreach($day['convoys'] as $convoy)
                                <p class="mb-0">{{ $convoy->start_time->format('H:i') }} - {{ $convoy->title }} @if($convoy->lead !== 'На месте разберёмся') (ведёт <b>{{ $convoy->lead }}</b>) @endif</p>
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
                            <a href="{{ route('evoque.convoys.plans.book', $loop->index) }}" class="book-convoy btn btn-outline-warning btn-sm">Забронировать конвой</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
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
                                <option value="На месте разберёмся" @if($convoy->lead === 'На месте разберёмся') selected @endif >На месте разберёмся</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->nickname }}" @if($member->nickname === $convoy->lead) selected @endif >{{ $member->nickname }}</option>
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
