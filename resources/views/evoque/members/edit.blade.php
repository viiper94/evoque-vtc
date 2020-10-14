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
            <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="roles">Должность (выбрать несколько с зажатым Ctrl)</label>
                        <select multiple class="form-control" id="roles" name="roles[]" size="14">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @if($member->role->contains($role->id)) selected @endif >{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="sort" name="sort" @if($member->sort) checked @endif>
                        <label class="custom-control-label" for="sort">Показывать вверху списка</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <h5 class="col-12">Игровая статистика</h5>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="scores">Баллы</label>
                        <input type="number" class="form-control" id="scores" name="scores" value="{{ $member->scores }}" placeholder="∞">
                        @if($errors->has('scores'))
                            <small class="form-text">{{ $errors->first('scores') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="money">Эвики</label>
                        <input type="text" class="form-control" id="money" name="money" value="{{ $member->money }}" placeholder="∞">
                        @if($errors->has('money'))
                            <small class="form-text">{{ $errors->first('money') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="convoys">Посещение конвоев</label>
                        <input type="number" class="form-control" id="convoys" name="convoys" value="{{ $member->convoys }}" required>
                        @if($errors->has('convoys'))
                            <small class="form-text">{{ $errors->first('convoys') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <h5 class="col-12">Отпуски</h5>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vacations">Использовано отпусков</label>
                        <input type="number" class="form-control" id="vacations" name="vacations" value="{{ $member->vacations }}">
                        @if($errors->has('vacations'))
                            <small class="form-text">{{ $errors->first('vacations') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="on_vacation_till">В отпуске до</label>
                        <input type="text" class="form-control" id="on_vacation_till" name="on_vacation_till" value="{{ $member->on_vacation_till ? $member->on_vacation_till->format('d.m.Y') : '' }}" autocomplete="off">
                        @if($errors->has('on_vacation_till'))
                            <small class="form-text">{{ $errors->first('on_vacation_till') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-outline-warning btn-lg"><i class="fas fa-save"></i> Сохранить</button>
                @if(\Illuminate\Support\Facades\Auth::user()->id !== $member->user->id && Gate::forUser($member->user)->denies('admin'))
                    <a href="{{ route('evoque.admin.members.fire', $member->id) }}" class="btn btn-lg btn-outline-danger ml-5"
                       onclick="return confirm('Уволить этого сотрудника?')"><i class="fas fa-user-times"></i> Уволить</a>
                @endif
            </div>
        </form>
        <div class="member-changelog">
            @if(count($member->audits) > 0)
                <h3 class="text-primary">История изменений</h3>
                @foreach($member->audits as $item)
                    <div class="changelog-item mb-3 table-responsive">
                        <table class="table table-dark table-bordered table-hover">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-primary">Отредактировал {{ $item->user->member->nickname }}, {{ $item->created_at->format('d.m.Y в H:i') }}</th>
                            </tr>
                            <tr>
                                <th scope="col">Параметр</th>
                                <th scope="col">Было</th>
                                <th scope="col">Стало</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item->old_values as $key => $value)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{!! $value !!}</td>
                                    <td>{!! $item->new_values[$key] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @endif
        </div>
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
