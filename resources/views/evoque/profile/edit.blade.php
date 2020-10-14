@extends('layout.index')

@section('title')
    Редактирование профиля | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.datetimepicker.min.css">
    <script src="/js/jquery.datetimepicker.full.min.js"></script>
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        <h2 class="mt-3 text-primary">Редактирование профиля</h2>
        <form method="post">
            @csrf
            <div class="form-group">
                <label for="name">Имя и Фамилия</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required>
                @if($errors->has('name'))
                    <small class="form-text">{{ $errors->first('name') }}</small>
                @endif
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="city">Город</label>
                    <input type="text" class="form-control" name="city" id="city" value="{{ $user->city }}" required>
                    @if($errors->has('city'))
                        <small class="form-text">{{ $errors->first('city') }}</small>
                    @endif
                </div>
                <div class="form-group col-sm-6">
                    <label for="country">Страна</label>
                    <input type="text" class="form-control" name="country" id="country" value="{{ $user->country }}" required>
                    @if($errors->has('country'))
                        <small class="form-text">{{ $errors->first('country') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="birth_date">Дата рождения</label>
                <input type="text" class="form-control" name="birth_date" id="birth_date" value="{{ $user->birth_date ? $user->birth_date->format('d.m.Y') : '' }}" autocomplete="off" required>
                @if($errors->has('birth_date'))
                    <small class="form-text">{{ $errors->first('birth_date') }}</small>
                @endif
            </div>
            <div class="form-group">
                <label for="vk">Ссылка ВК</label>
                <input type="url" class="form-control" name="vk" id="vk" value="{{ $user->vk }}">
                @if($errors->has('vk'))
                    <small class="form-text">{{ $errors->first('vk') }}</small>
                @endif
            </div>
            <div class="row text-center justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg">Сохранить</button>
            </div>
        </form>
    </div>

    <script>
        $('#birth_date').datetimepicker({
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
            theme: 'dark',
            dayOfWeekStart: '1',
            timepicker: false,
            scrollInput: false
        });
        $.datetimepicker.setLocale('ru');
    </script>

@endsection
