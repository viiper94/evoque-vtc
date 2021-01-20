@extends('layout.index')

@section('title')
    Сотрудники | @lang('general.vtc_evoque')
@endsection

@section('assets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.floatingscroll.css">
    <script src="/js/jquery.floatingscroll.min.js"></script>
@endsection

@section('content')
<div class="container-fluid pt-5 members-table">
    @include('layout.alert')
    <h2 class="mt-3 text-center text-primary">Сотрудники ВТК EVOQUE</h2>
    <div class="table-responsive mb-5" data-fl-scrolls>
        <table class="table table-dark table-bordered table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ник в игре</th>
                <th scope="col">Возраст</th>
                <th scope="col">Должность</th>
                <th scope="col">Баллы</th>
                <th scope="col">Эвики</th>
                <th scope="col" class="with-btn">
                    @can('resetActivity', \App\Member::class)
                        <a class="reset-btn text-shadow" data-token="{{ csrf_token() }}">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    @endcan
                    <span>Конвоев<br>за неделю</span>
                </th>
                <th scope="col">В отпуске<br>до</th>
                <th scope="col">Исп.<br>отпусков</th>
                <th scope="col">Имя</th>
                <th scope="col">Город/Страна</th>
                <th scope="col">Ссылки</th>
                <th scope="col">Дата<br>вступления</th>
                <th scope="col">Номер</th>
            </tr>
            </thead>
            <tbody>
            @php $i = 1; @endphp
            @foreach($roles as $role_group)
                <tr>
                    <th colspan="14" class="text-center">
                        {{ $role_group[0]->group }}
                        @if($role_group[0]->description)
                            <a tabindex="0" data-toggle="popover" data-trigger="focus" data-content="{{ $role_group[0]->description }}"><i class="fas fa-info-circle"></i></a>
                        @endif
                    </th>
                </tr>
                @foreach($role_group as $role)
                    @foreach($role->members as $member)
                        @if($member->topRole() == $role->id)
                            <tr class="member-{{ $member->id }}">
                                <td>{{ $i++ }}</td>
                                <td><b>{{ $member->nickname }}</b></td>
                                <td @if($member->user->birth_date && $member->user->birth_date->format('d-m') == \Carbon\Carbon::now()->format('d-m')) class="text-warning font-weight-bold" @endif>
                                    <b>{{ $member->user->birth_date ? $member->user->birth_date->age .' '. trans_choice('год|года|лет', $member->user->birth_date->age) : '–' }}</b>
                                </td>
                                <td>
                                    @foreach($member->role as $item)
                                        {{ $item->title }}@if(!$loop->last),@endif
                                    @endforeach
                                </td>
                                @if($member->isTrainee())
                                    <td colspan="2">Испытательный срок до {{ $member->trainee_until ? $member->trainee_until->format('d.m') : $member->join_date->addDays(10)->format('d.m') }}</td>
                                    <td class="member-convoys @if($member->isTraineeExpired() && $member->trainee_convoys < 4) text-danger font-weight-bold @endif">
                                        @can('setActivity', \App\Member::class)
                                            <a class="add-btn text-shadow" data-amount="1" data-target="посещение" data-id="{{ $member->id }}" data-nickname="{{ $member->nickname }}" data-token="{{ csrf_token() }}">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        @endcan
                                        <span class="number trainee">{{ $member->trainee_convoys }}</span><span>/4</span>
                                    </td>
                                @else
                                    <td class="member-scores">
                                        @can('setActivity', \App\Member::class)
                                            @if($member->scores !== null)
                                                <a class="add-btn text-shadow" data-amount="1" data-target="бал" data-id="{{ $member->id }}" data-nickname="{{ $member->nickname }}" data-token="{{ csrf_token() }}">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        <b class="number">{{ $member->scores ?? '∞' }}</b>
                                    </td>
                                    <td class="member-money">
                                        @can('setActivity', \App\Member::class)
                                            @if($member->money !== null)
                                                <a class="add-btn text-shadow" data-amount="0.5" data-target="эвика" data-id="{{ $member->id }}" data-nickname="{{ $member->nickname }}" data-token="{{ csrf_token() }}">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        <b class="number">{{ $member->money ?? '∞' }}</b>
                                    </td>
                                    <td class="member-convoys @if(\Carbon\Carbon::now()->format('N') == 7 && $member->convoys === 0 && !$member->onVacation())text-danger font-weight-bold @endif">
                                        @can('setActivity', \App\Member::class)
                                            <a class="add-btn text-shadow" data-amount="1" data-target="посещение" data-id="{{ $member->id }}" data-nickname="{{ $member->nickname }}" data-token="{{ csrf_token() }}">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        @endcan
                                        <span class="number">{{ $member->convoys }}</span>
                                    </td>
                                @endif
                                <td>{{ !isset($member->on_vacation_till) ? '–' : $member->on_vacation_till->isoFormat('DD.MM.Y') }}</td>
                                <td>{{ $member->vacations }}</td>
                                <td>
                                    @can('update', \App\Member::class)
                                        <a href="{{ route('evoque.profile', $member->user->id) }}" target="_blank">{{ $member->user->name }}</a>
                                    @else
                                        {{ $member->user->name }}
                                    @endcan
                                </td>
                                <td>{{ $member->getPlace() }}</td>
                                <td class="icon-link">
                                    @isset($member->user->vk)
                                        <a href="{{ $member->user->vk }}" target="_blank" class="mr-3"><i class="fab fa-vk"></i></a>
                                    @endisset
                                    @isset($member->user->discord_id)
                                        <a href="https://discordapp.com/users/{{ $member->user->discord_id }}" target="_blank" class="mr-3"><i class="fab fa-discord"></i></a>
                                    @endisset
                                    @isset($member->user->steamid64)
                                        <a href="https://steamcommunity.com/profiles/{{ $member->user->steamid64 }}" target="_blank" class="mr-3"><i class="fab fa-steam-square"></i></a>
                                    @endisset
                                    @isset($member->user->truckersmp_id)
                                        @can('seeBans', \App\Member::class)
                                            @if($member->isBanned())
                                                <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}"
                                                   target="_blank" class="text-danger" data-toggle="popover" data-trigger="hover"
                                                   data-content="Забанен {{ isset($member->tmp_banned_until) ? 'до '.$member->tmp_banned_until->isoFormat('DD.MM.YYYY, HH:MM') : 'перманентно!' }}">
                                                    <i class="fas fa-truck-pickup"></i>
                                                </a>
                                            @else
                                                <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}">
                                                    <i class="fas fa-truck-pickup"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}">
                                                <i class="fas fa-truck-pickup"></i>
                                            </a>
                                        @endcan
                                    @endisset
                                    @if(\Illuminate\Support\Facades\Auth::user()->can('update', \App\Member::class) ||
                                            \Illuminate\Support\Facades\Auth::user()->can('updateRpStats', \App\Member::class))
                                        <a href="{{ route('evoque.admin.members.edit', $member->id) }}" class="ml-3"><i class="fas fa-user-edit"></i></a>
                                    @endcan
                                </td>
                                <td>{{ !isset($member->join_date) ? '–' : $member->join_date->isoFormat('DD.MM.Y') }}</td>
                                <td class="plate-img p-0">
                                    @if($member->isTrainee())
                                        <img src="/assets/img/u.png">
                                    @elseif($member->plate)
                                        <img src="/images/plates/{{ $member->plate }}.png">
                                    @else
                                        <img src="/images/plates/empty.png">
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <th colspan="14">&nbsp;</th>
            </tr>
            </tbody>
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Ник в игре</th>
                <th scope="col">Возраст</th>
                <th scope="col">Должность</th>
                <th scope="col">Баллы</th>
                <th scope="col">Эвики</th>
                <th scope="col" class="with-btn">
                    @can('resetActivity', \App\Member::class)
                        <a class="reset-btn text-shadow" data-token="{{ csrf_token() }}">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    @endcan
                    <span>Конвоев<br>за неделю</span>
                </th>
                <th scope="col">В отпуске<br>до</th>
                <th scope="col">Исп.<br>отпусков</th>
                <th scope="col">Имя</th>
                <th scope="col">Город/Страна</th>
                <th scope="col">Ссылки</th>
                <th scope="col">Дата<br>вступления</th>
                <th scope="col">Номер</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div class="container mb-5">
    <h3 class="text-primary text-center">Начисление баллов и эвиков</h3>
    <table class="table table-dark table-hover table-bordered text-center">
        <tbody>
        <tr>
            <td>Закрытый конвой</td>
            <th>1 балл</th>
        </tr>
        <tr>
            <td>Открытый или совместный конвой</td>
            <th>2 балла</th>
        </tr>
        <tr>
            <td>Проведённый конвой</td>
            <th>2 балла + 1 эвик</th>
        </tr>
        <tr>
            <td>Ведущий нашей ВТК на открытом или совместном конвое другой ВТК</td>
            <th>2 балла + 0,5 эвика</th>
        </tr>
        </tbody>
    </table>
    <h6 class="text-center text-muted">Баллы зачисляются в том случае, если конвой пройден до конца, согласно установленного маршрута.</h6>
</div>

@endsection
