@extends('layout.index')

@section('title')
    Редактирование сотрудника | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5">
        <h2 class="mt-3 text-primary">Редактирование сотрудника {{ $member->nickname }}</h2>
        <form method="post" class="mb-5 edit-member">
            @csrf
            <div class="form-group">
                <label for="nickname">Игровой ник</label>
                <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $member->nickname }}" required>
                @if($errors->has('nickname'))
                    <small class="form-text">{{ $errors->first('nickname') }}</small>
                @endif
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($member->visible) checked @endif>
                <label class="custom-control-label" for="visible">Виден на сайте</label>
            </div>
            <div class="form-group">
                <label for="roles">Должность</label>
                <select multiple class="form-control" id="roles" name="roles[]" size="14">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @if($member->role->contains($role->id)) selected @endif >{{ $role->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="join_date">Дата присоединения</label>
                <input type="text" class="form-control" id="join_date" name="join_date" value="{{ $member->join_date->format('d.m.Y') }}" autocomplete="off">
                @if($errors->has('join_date'))
                    <small class="form-text">{{ $errors->first('join_date') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="plate">Номерной знак (ссылка на изображение)</label>
                <input type="url" class="form-control" id="plate" name="plate" value="{{ $member->plate }}">
                @if($errors->has('plate'))
                    <small class="form-text">{{ $errors->first('plate') }}</small>
                @endif
                @isset($member->plate) <img class="mt-1" src="{{ $member->plate }}"> @endisset
            </div>
            <h5>Игровая статистика</h5>
            <div class="form-group">
                <label for="convoys">Посещение конвоев</label>
                <input type="number" class="form-control" id="convoys" name="convoys" value="{{ $member->convoys }}" required>
                @if($errors->has('convoys'))
                    <small class="form-text">{{ $errors->first('convoys') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="scores">Баллы</label>
                <input type="number" class="form-control" id="scores" name="scores" value="{{ $member->scores }}" required>
                @if($errors->has('scores'))
                    <small class="form-text">{{ $errors->first('scores') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="money">Эвики</label>
                <input type="number" class="form-control" id="money" name="money" value="{{ $member->money }}" required>
                @if($errors->has('money'))
                    <small class="form-text">{{ $errors->first('money') }}</small>
                @endif
            </div>
            <h5>Отпуски</h5>
            <div class="form-group">
                <label for="vacations">Использовано отпусков</label>
                <input type="number" class="form-control" id="vacations" name="vacations" value="{{ $member->vacations }}">
                @if($errors->has('vacations'))
                    <small class="form-text">{{ $errors->first('vacations') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="on_vacation_till">В отпуске до</label>
                <input type="text" class="form-control" id="on_vacation_till" name="on_vacation_till" value="{{ $member->on_vacation_till ? $member->on_vacation_till->format('d.m.Y') : '' }}" autocomplete="off">
                @if($errors->has('on_vacation_till'))
                    <small class="form-text">{{ $errors->first('on_vacation_till') }}</small>
                @endif
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg">Сохранить</button>
            </div>
        </form>
    </div>

    <script>
        $('#join_date, #on_vacation_till').datetimepicker({
            i18n:{
                ru:{
                    months:[
                        'Январь','Февраль','Март','Апрель',
                        'Май','Июнь','Июль','Август',
                        'Сентябрь','Октябрь','Ноябрь','Декабрь',
                    ],
                    dayOfWeek:[
                        "Вс", "Пн", "Вт", "Ср",
                        "Чт", "Пт", "Сб",
                    ]
                }
            },
            format: 'd.m.Y',
            lang: 'ru',
            step: 30,
            theme: 'dark',
            dayOfWeekStart: '1',
            defaultTime: '19:30',
            timepicker: false,
            scrollInput: false
        });
        $.datetimepicker.setLocale('ru');
    </script>

@endsection
