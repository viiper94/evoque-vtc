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
                            <label for="nickname">@lang('attributes.nickname')</label>
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
                            <label class="custom-control-label" for="visible">Виден на сайте</label>
                        </div>
                        <div class="form-group">
                            <label for="join_date">@lang('attributes.join_date')</label>
                            <input type="text" class="form-control" id="join_date" name="join_date" value="{{ $member->join_date->format('d.m.Y') }}" autocomplete="off">
                            @if($errors->has('join_date'))
                                <small class="form-text">{{ $errors->first('join_date') }}</small>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label for="plate">@lang('attributes.plate')</label>
                                <input type="text" class="form-control" id="plate" name="plate" value="{{ $member->plate }}">
                                @if($errors->has('plate'))
                                    <small class="form-text">{{ $errors->first('plate') }}</small>
                                @endif
                            </div>
                            @isset($member->plate)
                                <div class="col-auto">
                                    <label>Превью</label><br>
                                    <img src="/images/plates/{{ $member->plate }}.png" style="height: 38px">
                                </div>
                            @endisset
                        </div>
                        <div class="form-group">
                            <label for="vk">@lang('attributes.vk')</label>
                            <input type="url" class="form-control" id="vk" name="vk" value="{{ $member->user->vk }}">
                            @if($errors->has('vk'))
                                <small class="form-text">{{ $errors->first('vk') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="sort" name="sort" @if($member->sort) checked @endif>
                            <label class="custom-control-label" for="sort">@lang('attributes.sort')</label>
                        </div>
                        <div class="form-group">
                            <label for="roles">Должность (выбрать несколько с зажатым Ctrl)</label>
                            @if($member->role->contains(0))
                                <input type="hidden" name="roles[]" value="0">
                            @endif
                            <select multiple class="form-control" id="roles" name="roles[]" size="14">
                                @foreach($roles as $role)
                                    @if($role->id !== 0)
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
                                <label for="trainee_until">@lang('attributes.trainee_until')</label>
                                <input type="text" class="form-control" id="trainee_until" name="trainee_until" value="{{ $member->trainee_until?->format('d.m.Y') ?? '' }}" autocomplete="off">
                                @if($errors->has('trainee_until'))
                                    <small class="form-text">{{ $errors->first('trainee_until') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="trainee_convoys">@lang('attributes.trainee_convoys')</label>
                                <input type="text" class="form-control" id="trainee_convoys" name="trainee_convoys" value="{{ $member->trainee_convoys }}" autocomplete="off">
                                @if($errors->has('trainee_convoys'))
                                    <small class="form-text">{{ $errors->first('trainee_convoys') }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col">
                        <h5>Игровая статистика</h5>
                        <div class="form-group">
                            <label for="scores">@lang('attributes.scores')</label>
                            <input type="number" class="form-control" id="scores" name="scores" value="{{ $member->scores }}" placeholder="∞">
                            @if($errors->has('scores'))
                                <small class="form-text">{{ $errors->first('scores') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="money">@lang('attributes.money')</label>
                            <input type="text" class="form-control" id="money" name="money" value="{{ $member->money }}" placeholder="∞">
                            @if($errors->has('money'))
                                <small class="form-text">{{ $errors->first('money') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="convoys">@lang('attributes.convoys')</label>
                            <input type="number" class="form-control" id="convoys" name="convoys" value="{{ $member->convoys }}" required>
                            @if($errors->has('convoys'))
                                <small class="form-text">{{ $errors->first('convoys') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <h5>Отпуски</h5>
                        <div class="form-group">
                            <label for="on_vacation_till">@lang('attributes.on_vacation_till')</label> <br>
                            <input type="hidden" id="on_vacation_till" name="on_vacation_till" value="{{ $member->on_vacation_till ? $member->on_vacation_till['from'].' - '.$member->on_vacation_till['to'] : '' }}">
                            @if($errors->has('on_vacation_till'))
                                <small class="form-text">{{ $errors->first('on_vacation_till') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="vacations">@lang('attributes.vacations')</label>
                            <input type="number" class="form-control" id="vacations" name="vacations" value="{{ $member->vacations }}">
                            @if($errors->has('vacations'))
                                <small class="form-text">{{ $errors->first('vacations') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @if($member->trashed())
                        @can('restore', \App\Member::class)
                            <a href="{{ route('evoque.admin.members.restore', $member->id) }}" class="btn btn-lg btn-outline-success ml-5"
                               onclick="return confirm('Восстановить этого сотрудника?')"><i class="fas fa-undo"></i> Восстановить</a>
                        @endcan
                    @else
                        <button type="submit" class="btn btn-outline-warning btn-lg"><i class="fas fa-save"></i> Сохранить</button>
                        @can('fire', $member)
                            <a href="{{ route('evoque.admin.members.fire', $member->id) }}" class="btn btn-lg btn-outline-danger ml-5"
                               onclick="return confirm('Уволить этого сотрудника?')"><i class="fas fa-user-times"></i> Уволить</a>
                            <a href="{{ route('evoque.admin.members.fire', [$member->id, 'soft']) }}" class="btn btn-lg btn-outline-warning ml-5"
                               onclick="return confirm('Уволить этого сотрудника с восстановлением?')"><i class="fas fa-user-times"></i> Уволить с восстановлением</a>
                        @endcan
                    @endif
                </div>
            </form>
        @endcan

        @can('updatePermissions', $member)
            <form action="{{ route('evoque.admin.members.editPermissions', $member->id) }}" method="post" class="member-permissions">
                @csrf
                <div class="accordion my-5" id="accordion">
                    <div class="card card-dark">
                        <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne">
                            <h5 class="mb-0">Редактирование прав на сайте</h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Сохранить права</button>
                                </div>
                                @foreach(\App\Role::$permission_list as $category => $perms)
                                    <div class="col-md-6 my-3">
                                        <h5 class="text-primary">
                                            @lang('roles.'.$category)
                                        </h5>
                                        @foreach($perms as $item)
                                            <div class="custom-control custom-checkbox @if($loop->index === 0) mb-4 @endif">
                                                <input type="hidden" name="permissions[{{ $item }}]" value="off" disabled>
                                                <input type="checkbox" class="custom-control-input" id="{{ $item }}" name="permissions[{{ $item }}]" value="on"
                                                       {{ $member->getMemberPermissionCheckboxState($item, $rolePermissions) }}>
                                                <label class="custom-control-label  @if($loop->index === 0) text-danger @endif" for="{{ $item }}">
                                                    @lang('roles.'.$item)
                                                </label>
                                                <a class="reset-permission text-primary">Сбросить</a>
                                            </div>
                                        @endforeach
                                        <hr class="border-secondary">
                                    </div>
                                @endforeach
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-warning"><i class="fas fa-save"></i> Сохранить права</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <h3 class="text-primary mt-3">История изменений</h3>
            @include('layout.changelog', ['items' => $member->audits, 'granularity' => new cogpowered\FineDiff\Granularity\Word])
            <div class="row justify-content-center mb-5">
                <a href="{{ route('evoque.admin.members.changelog', $member->id) }}" class="btn btn-sm btn-outline-warning ml-3 btn-lg"><i class="fas fa-history"></i> Полная история изменений</a>
            </div>
        @endcan

    </div>

    <script>
        const pickerVacation = new Litepicker({
            element: document.getElementById('on_vacation_till'),
            plugins: ['mobilefriendly'],
            inlineMode: true,
            lang: 'ru-RU',
            singleMode: false,
            showTooltip: false,
            numberOfMonths: 2,
            numberOfColumns: 2,
            resetButton: true
        });
        const pickerJoin = new Litepicker({
            element: document.getElementById('join_date'),
            plugins: ['mobilefriendly'],
            lang: 'ru-RU',
            format: 'DD.MM.YYYY'
        });
        const pickerTrainee = new Litepicker({
            element: document.getElementById('trainee_until'),
            plugins: ['mobilefriendly'],
            lang: 'ru-RU',
            format: 'DD.MM.YYYY'
        });
    </script>

@endsection
