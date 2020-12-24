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
        @can('update', \App\Member::class)
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
                            @can('fire', $member)
                                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($member->visible) checked @endif>
                            @else
                                <input type="checkbox" class="custom-control-input" id="visible" name="visible" @if($member->visible) checked @endif disabled>
                                <input type="hidden" name="visible" value="{{ $member->visible ? 'on' : 'off' }}">
                            @endcan
                            <label class="custom-control-label" for="visible">Виден на сайте (снять галочку, чтобы уволить с восстановлением)</label>
                        </div>
                        <div class="form-group">
                            <label for="join_date">Дата присоединения</label>
                            <input type="text" class="form-control" id="join_date" name="join_date" value="{{ $member->join_date->format('d.m.Y') }}" autocomplete="off">
                            @if($errors->has('join_date'))
                                <small class="form-text">{{ $errors->first('join_date') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="plate">Номерной знак (три цифры)</label>
                            <input type="text" class="form-control" id="plate" name="plate" value="{{ $member->plate }}">
                            @if($errors->has('plate'))
                                <small class="form-text">{{ $errors->first('plate') }}</small>
                            @endif
                            @isset($member->plate) <img class="mt-1" src="/images/plates/{{ $member->plate }}.png"> @endisset
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="sort" name="sort" @if($member->sort) checked @endif>
                            <label class="custom-control-label" for="sort">Показывать вверху списка</label>
                        </div>
                        <div class="form-group">
                            <label for="roles">Должность (выбрать несколько с зажатым Ctrl)</label>
                            <select multiple class="form-control" id="roles" name="roles[]" size="14">
                                @foreach($roles as $role)
                                    @if($role->id === 0)
                                        <option value="0" disabled @if($member->role->contains($role->id)) selected @endif>{{ $role->title }}</option>
                                    @else
                                        <option value="{{ $role->id }}" @if($member->role->contains($role->id)) selected @endif @cannot('updateRoles', $member) disabled @endcannot>{{ $role->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @cannot('updateRoles', $member)
                                @foreach($member->role as $role)
                                    <input type="hidden" name="roles[]" value="{{ $role->id }}">
                                @endforeach
                            @endcannot
                        </div>
                        @if($member->isTrainee())
                            <div class="form-group">
                                <label for="trainee_until">Испытательный срок до</label>
                                <input type="text" class="form-control" id="trainee_until" name="trainee_until" value="{{ $member->trainee_until ? $member->trainee_until->format('d.m.Y') : '' }}" autocomplete="off">
                                @if($errors->has('trainee_until'))
                                    <small class="form-text">{{ $errors->first('trainee_until') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="trainee_convoys">Конвоев на исп. сроке</label>
                                <input type="text" class="form-control" id="trainee_convoys" name="trainee_convoys" value="{{ $member->trainee_convoys }}" autocomplete="off">
                                @if($errors->has('trainee_convoys'))
                                    <small class="form-text">{{ $errors->first('trainee_convoys') }}</small>
                                @endif
                            </div>
                        @endif
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
                            <label for="convoys">Посещение конвоев за неделю</label>
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
                    @can('fire', $member)
                        <a href="{{ route('evoque.admin.members.fire', $member->id) }}" class="btn btn-lg btn-outline-danger ml-5"
                           onclick="return confirm('Уволить этого сотрудника?')"><i class="fas fa-user-times"></i> Уволить</a>
                    @endcan
                </div>
            </form>
        @endcan
        @can('updateRpStats', \App\Member::class)
            @if(count($member->stats) > 0)
                <div class="member-rp-stat mb-5">
                    <h3 class="mt-3 text-primary">Редактирование статистики рейтинговых перевозок</h3>
                    <div class="row">
                        @foreach($member->stats as $stat)
                            <div class="col-md-{{ count($member->stats) > 1 ? '6' : '12' }} ets2">
                                <h5>{{ strtoupper($stat->game) }}</h5>
                                <h5>Всего</h5>
                                <form method="post" action="{{ route('evoque.rp.stat.edit', $stat->id) }}" class="row">
                                    @csrf
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="distance_total">Пройденное расстояние, км</label>
                                            <input type="number" class="form-control" id="distance_total" name="distance_total" value="{{ $stat->distance_total }}" autocomplete="off">
                                            @if($errors->has('distance_total'))
                                                <small class="form-text">{{ $errors->first('distance_total') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weight_total">Тоннаж, т</label>
                                            <input type="number" class="form-control" id="weight_total" name="weight_total" value="{{ $stat->weight_total }}" autocomplete="off">
                                            @if($errors->has('weight_total'))
                                                <small class="form-text">{{ $errors->first('weight_total') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity_total">Кол-во грузов</label>
                                            <input type="number" class="form-control" id="quantity_total" name="quantity_total" value="{{ $stat->quantity_total }}" autocomplete="off">
                                            @if($errors->has('quantity_total'))
                                                <small class="form-text">{{ $errors->first('quantity_total') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="level">Уровень в игре</label>
                                            <input type="number" class="form-control" id="level" name="level" value="{{ $stat->level }}" autocomplete="off">
                                            @if($errors->has('level'))
                                                <small class="form-text">{{ $errors->first('level') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <h5 class="col-12">За неделю</h5>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="distance">Пройденное расстояние, км</label>
                                            <input type="number" class="form-control" id="distance" name="distance" value="{{ $stat->distance }}" autocomplete="off">
                                            @if($errors->has('distance'))
                                                <small class="form-text">{{ $errors->first('distance') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weight">Тоннаж, т</label>
                                            <input type="number" class="form-control" id="weight" name="weight" value="{{ $stat->weight }}" autocomplete="off">
                                            @if($errors->has('weight'))
                                                <small class="form-text">{{ $errors->first('weight') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity">Кол-во грузов</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $stat->quantity }}" autocomplete="off">
                                            @if($errors->has('quantity'))
                                                <small class="form-text">{{ $errors->first('quantity') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bonus">Бонус</label>
                                            <input type="number" class="form-control" id="bonus" name="bonus" value="{{ $stat->bonus }}" autocomplete="off">
                                            @if($errors->has('bonus'))
                                                <small class="form-text">{{ $errors->first('bonus') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Сохранить статистику по {{ strtoupper($stat->game) }}</button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endcan
        @can('update', \App\Member::class)
            @if(count($member->audits) > 0)
                <div class="member-changelog mb-5">
                    <h3 class="text-primary">История изменений</h3>
                    @foreach($member->audits as $item)
                        @if($item->old_values)
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
                        @endif
                    @endforeach
                </div>
            @endif
        @endcan
    </div>

    <script>
        $('#join_date, #on_vacation_till, #trainee_until').datetimepicker({
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
