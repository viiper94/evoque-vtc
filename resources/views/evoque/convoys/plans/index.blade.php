@extends('layout.index')

@section('title')
    Планы по конвоям | @lang('general.vtc_evoque')
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
    <div class="table-responsive pb-5">
        <table class="table table-dark table-hover text-center mb-0">
            <thead>
            <tr>
                <th class="text-right d-none d-md-block">День</th>
                <th>Дата</th>
                <th class="text-left">Конвои</th>
            </tr>
            </thead>
            <tbody>
            @foreach($days as $date => $day)
                <tr @if($loop->iteration === 1) class="table-active" @endif>
                    <td class="text-right d-md-table-cell d-none">
                        <span>{{ $day['date']->isoFormat('dddd') }}</span>
                    </td>
                    <td>
                        <p class="d-block d-md-none">{{ $day['date']->isoFormat('dd') }}</p>
                        {{ $date }}
                    </td>
                    <td class="text-left convoys">
                        @foreach($day['convoys'] as $type => $convoys)
                            @foreach($convoys as $convoy)
                                @if($convoy)
                                    <p class="my-2">
                                        {{ $convoy->getType() }} -
                                        <b class="text-primary">{{ $convoy->start_time->format('H:i') }}</b> -
                                        {{ $convoy->title }} @if($convoy->lead !== 'На месте разберёмся') (ведёт <b>{{ $convoy->lead }}</b>) @endif
                                    </p>
                                @else
                                    <p class="my-2">
                                        {{ \App\Convoy::getTypeByNum($type) }} -
                                        @can('quickBook', \App\Convoy::class)
                                            <a data-date="{{ $day['date']->format('d.m.Y') }}" data-toggle="modal" data-target="#book-modal" class="book-convoy text-primary">Создать конвой</a>
                                        @elsecan('book', \App\Convoy::class)
                                            <a href="{{ route('evoque.convoys.plans.book', [$loop->parent->parent->index, $type]) }}" class="book-convoy text-primary">Забронировать @if($loop->parent->parent->iteration === 1) внеплановый @endif конвой</a>
                                        @else
                                            Свободно
                                        @endcan
                                    </p>
                                @endif
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr class="m-auto w-25 border-primary">
    <section class="features lead-rules text-center row pt-5 pb-5 justify-content-around">
        <h2 class="col-12 mb-3">Правила для ETS2</h2>
        <div class="feature col-lg-2 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h1 class="display-4 font-weight-bold">SIM</h1>
            <hr class="m-auto pb-3">
            <p>1800+ км</p>
        </div>
        <div class="feature col-lg-2 col-sm-12 mb-md-0 mb-5">
            <h1 class="display-4 font-weight-bold">ARC</h1>
            <hr class="m-auto pb-3">
            <p>2300+ км</p>
        </div>
        <div class="feature col-lg-2 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Тяжёлые грузы</h2>
            <hr class="m-auto pb-3">
            <p>1400+ км</p>
        </div>
        <div class="feature col-lg-3 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Легковые и головастики</h2>
            <hr class="m-auto pb-3">
            <p>SIM: 2000+ км<br>ARC: 2500+ км</p>
        </div>
        <div class="feature col-lg-3 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Скорость ведущего</h2>
            <hr class="m-auto pb-3">
            <p>SIM: до 100 км/ч<br>ARC: до 150 км/ч</p>
        </div>
    </section>
    <section class="features lead-rules text-center row justify-content-around">
        <h2 class="col-12 mb-3">Правила для ATS</h2>
        <div class="feature col-lg-2 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h1 class="display-4 font-weight-bold">SIM</h1>
            <hr class="m-auto pb-3">
            <p>1900+ км</p>
        </div>
        <div class="feature col-lg-2 col-sm-12 mb-md-0 mb-5">
            <h1 class="display-4 font-weight-bold">ARC</h1>
            <hr class="m-auto pb-3">
            <p>2400+ км</p>
        </div>
        <div class="feature col-lg-2 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Тяжёлые грузы</h2>
            <hr class="m-auto pb-3">
            <p>1400+ км</p>
        </div>
        <div class="feature col-lg-3 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Легковые и головастики</h2>
            <hr class="m-auto pb-3">
            <p>SIM: 2300+ км<br>ARC: 2500+ км</p>
        </div>
        <div class="feature col-lg-3 col-sm-4 col-sm-12 mb-md-0 mb-5">
            <h2 class="text-primary font-weight-bold">Скорость ведущего</h2>
            <hr class="m-auto pb-3">
            <p>SIM: до 115 км/ч<br>ARC: до 150 км/ч</p>
        </div>
    </section>
</div>

@can('quickBook', \App\Convoy::class)
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
                            <input type="text" class="form-control" id="title" name="title" value="Закрытый конвой" required>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="date">Дата выезда</label>
                                <input type="text" class="form-control" id="date" name="date" autocomplete="off" readonly>
                            </div>
                            <div class="form-group col">
                                <label for="datetimepicker">Время выезда</label>
                                <select name="time" id="time" class="form-control" required>
                                    @foreach($types as $type => $time)
                                        <optgroup label="{{ \App\Convoy::getTypeByNum($type) }}">
                                            @foreach($time as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                            <option disabled selected></option>
                                        </optgroup>
                                    @endforeach
                                </select>
{{--                                <input type="text" class="form-control" id="time" name="time" autocomplete="off" required>--}}
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
@endcan

@endsection
