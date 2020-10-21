@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5 members-table">
        @include('layout.alert')
        <h2 class="mt-3 text-center text-primary">Статистика рейтинговых перевозок по {{ strtoupper($game) }}</h2>
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-hover">
                <thead>
                <tr>
                    <th colspan="5"></th>
                    <th colspan="3">Всего</th>
                    <th colspan="4">За неделю</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Ник в игре</th>
                    <th>Ссылки</th>
                    <th>Уровень в игре</th>
                    <th>Ступень</th>
                    <th>Пройденное расстояние</th>
                    <th>Тоннаж</th>
                    <th>Кол-во грузов</th>
                    <th>Пройденное расстояние</th>
                    <th>Бонус</th>
                    <th>Тоннаж</th>
                    <th>Кол-во грузов</th>
                </tr>
                </thead>
                <tbody>
                @php $i = 1; @endphp
                @foreach($roles as $role_group)
                    <tr>
                        <th colspan="11" class="text-center text-primary">{{ $role_group[0]->group }}</th>
                    </tr>
                    @foreach($role_group as $role)
                        @foreach($role->members as $member)
                            @if($member->topRole() == $role->id && $member->stat)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td><b>{{ $member->nickname }}</b></td>
                                    <td class="icon-link">
                                        @isset($member->user->vk)
                                            <a href="{{ $member->user->vk }}" target="_blank" class="mr-3"><i class="fab fa-vk"></i></a>
                                        @endisset
                                        @isset($member->user->steamid64)
                                            <a href="https://steamcommunity.com/profiles/{{ $member->user->steamid64 }}" target="_blank" class="mr-3"><i class="fab fa-steam-square"></i></a>
                                        @endisset
                                        @isset($member->user->truckersmp_id)
                                            <a href="https://truckersmp.com/user/{{ $member->user->truckersmp_id }}" target="_blank"><i class="fas fa-truck-pickup"></i></a>
                                        @endisset
                                        @can('manage_table')
                                            <a href="{{ route('evoque.admin.members.edit', $member->id) }}" class="ml-3"><i class="fas fa-user-edit"></i></a>
                                        @endcan
                                    </td>
                                    <td>{{ $member->stat->level }}</td>
                                    <td><b>{{ $member->stat->getStage() }}</b></td>
                                    <td>{{ $member->stat->distance_total }} км</td>
                                    <td>{{ $member->stat->weight_total }} т</td>
                                    <td>{{ $member->stat->quantity_total }}</td>
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
        @can('manage_rp')
            <div class="row justify-content-center mb-5">
                <a href="{{ route('evoque.rp.results.create', $game) }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"
                    onclick="return confirm('Вы уверены?')">
                    <i class="fas fa-sync-alt"></i> Обнулить результаты за неделю
                </a>
            </div>
        @endcan
    </div>
    <div class="container mt-5 mb-5">
        <h3 class="text-center text-primary">
            Вознаграждения
        </h3>
        <div class="table-responsive">
            <table class="table table-dark table-bordered text-center rewards-table table-hover">
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
                            <th class="w-25">{{ $km }}</th>
                            <td>{{ $reward }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
