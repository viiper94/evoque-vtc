@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    @include('layout.alert')
    <h2 class="pt-5 mt-3 text-center text-primary">Статистика рейтинговых перевозок</h2>
    <div class="row justify-content-center mb-3 mr-0">
        <ul class="nav nav-pills text-center">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#ets2">Euro Truck Simulator 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ats">American Truck Simulator</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        @foreach($roles as $game => $game_roles)
            <div class="tab-pane @if($loop->first) show active @endif" id="{{ $game }}" role="tabpanel" aria-labelledby="home-tab">
                <div class="container-fluid members-table">
                    <div class="table-responsive">
                        <table class="table table-sm table-dark table-bordered table-hover">
                            <thead>
                            <tr>
                                <th colspan="4"></th>
                                <th colspan="3" class="border-left-5 border-right-5">Всего</th>
                                <th colspan="4">За неделю</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Ник в игре</th>
                                <th>Уровень в игре</th>
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
                                    <th colspan="11" class="text-center text-primary">{{ $role_group[0]->group }}</th>
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
                        <i class="fas fa-trophy"></i> Итоги за неделю
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
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\RpStats::$stages[$game] as $km => $reward)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <th class="w-25 stage-{{ $loop->iteration }}">{{ $km }}</th>
                                    <td>{{ $reward }}</td>
                                </tr>
                            @endforeach
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
