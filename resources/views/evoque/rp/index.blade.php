@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    @include('layout.alert')
    <h2 class="pt-5 mt-3 text-center text-primary">Статистика рейтинговых перевозок</h2>
    <div class="row justify-content-center mb-3 mr-0 ml-0">
        <ul class="nav nav-pills justify-content-center">
            @foreach($roles as $game => $game_roles)
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => $loop->first]) data-toggle="tab" href="#{{ $game }}">@lang('general.'.$game)</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="tab-content">
        @foreach($roles as $game => $game_roles)
            <div @class(['tab-pane', 'show active' => $loop->first]) id="{{ $game }}" role="tabpanel">
                <div class="container-fluid members-table">
                    <div class="table-responsive">
                        <table class="table table-sm table-dark table-bordered table-hover">
                            <thead>
                            <tr>
                                <th colspan="5"></th>
                                <th colspan="3" class="border-left-5 border-right-5">Всего</th>
                                <th colspan="4">За 10 дней</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Ник в игре</th>
                                <th>Уровень в игре</th>
                                <th>
                                    @if($game === 'ets2')Уровень в ProMods @endif
                                </th>
                                <th>Ступень</th>
                                <th class="border-left-5">Пройденное расстояние</th>
                                <th>Тоннаж</th>
                                <th class="border-right-5">Кол-во грузов</th>
                                <th>Пройденное расстояние</th>
                                <th>Бонус</th>
                                <th>Тоннаж</th>
                                <th>Кол-во грузов</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1; @endphp
                            @foreach($game_roles as $role_group)
                                <tr>
                                    <th colspan="12" class="text-center text-primary">{{ $role_group[0]->group }}</th>
                                </tr>
                                @foreach($role_group as $role)
                                    @foreach($role->members as $member)
                                        @if($member->topRole() == $role->id && $member->stat)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td class="stage-{{ $member->stat->getStage() }}">
                                                    @can('updateRpStats', \App\Member::class)
                                                        <a href="{{ route('evoque.admin.members.edit', $member->id) }}" class="ml-3"><b>{{ $member->nickname }}</b></a>
                                                    @else
                                                        <b>{{ $member->nickname }}</b>
                                                    @endcan
                                                </td>
                                                <td>{{ $member->stat->level }}</td>
                                                <td>
                                                @if($member->stat->game === 'ets2')
                                                    {{ $member->stat->level_promods }}
                                                    @endif
                                                </td>
                                                <td><b>{{ $member->stat->getStage() }}</b></td>
                                                <td class="border-left-5">{{ $member->stat->distance_total }} км</td>
                                                <td>{{ $member->stat->weight_total }} т</td>
                                                <td class="border-right-5">{{ $member->stat->quantity_total }}</td>
                                                <td>{{ $member->stat->distance }} км</td>
                                                <td>{{ $member->stat->bonus }} км</td>
                                                <td>{{ $member->stat->weight }} т</td>
                                                <td>{{ $member->stat->quantity }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center mb-5 mr-0">
                    <a href="{{ route('evoque.rp.weekly') }}" class="btn btn-outline-success ml-3 mt-3 btn-lg">
                        <i class="fas fa-trophy"></i> Итоги за 10 дней
                    </a>
                </div>
                <h3 class="text-center text-primary mt-3">
                    Вознаграждения
                </h3>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-sm table-dark table-bordered text-center rewards-table table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Километраж</th>
                                <th>Вознаграждение (одно на каждую ступень)</th>
                                @can('updateRewards', \App\RpReport::class)
                                    <th>
                                        <a href="{{ route('evoque.rp.rewards.edit') }}" class="mx-2"
                                           data-title="Добавить награду" data-toggle="tooltip" data-placement="left">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @isset($rewards[$game])
                                @foreach($rewards[$game] as $reward)
                                    <tr>
                                        <th>{{ $reward->stage }}</th>
                                        <th class="w-25 stage-{{ $reward->stage }}">{{ $reward->km }}</th>
                                        <td>{{ $reward->reward }}</td>
                                        @can('updateRewards', \App\RpReport::class)
                                            <td style="min-width: 90px;">
                                                <a href="{{ route('evoque.rp.rewards.edit', $reward->id) }}" class="mx-2"
                                                   data-title="Редактировать" data-toggle="tooltip">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <a href="{{ route('evoque.rp.rewards.delete', $reward->id) }}" class="mx-2"
                                                   data-title="Удалить" data-toggle="tooltip" onclick="return confirm('Удалить эту награду?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="container mt-5 mb-5">
        <section class="features lead-rules text-center row pt-5 pb-5 justify-content-around">
            <h2 class="col-12 mb-3">Бонусные коефициенты</h2>
            @foreach(\App\RpStats::$bonus as $weight => $k)
                <div class="feature col-lg-2 col-sm-4 col-sm-12 mb-md-0 mb-5">
                    <h3 class="font-weight-bold text-primary">{{ $weight }}</h3>
                    <hr class="m-auto pb-3">
                    <h4>{{ $k }}</h4>
                </div>
            @endforeach
        </section>
    </div>

@endsection
