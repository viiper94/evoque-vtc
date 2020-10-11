@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5 members-table">
        @include('layout.alert')
        <h2 class="pt-3 text-center text-primary">Статистика рейтинговых перевозок по {{ strtoupper($game) }}</h2>
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-hover">
                <thead>
                <tr>
                    <th colspan="3"></th>
                    <th colspan="3">Всего</th>
                    <th colspan="4">За неделю</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Ник в игре</th>
                    <th>Уровень в игре</th>
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
                    @if(count($role_group[0]->members) < 1) @continue @endif
                    <tr>
                        <th colspan="10" class="text-center text-primary">{{ $role_group[0]->group }}</th>
                    </tr>
                    @foreach($role_group as $role)
                        @foreach($role->members as $member)
                            @if($member->topRole() == $role->id && $member->stat)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td><b>{{ $member->nickname }}</b></td>
                                    <td>{{ $member->stat->level }}</td>
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
                <a href="{{ route('evoque.rp.reset') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg">
                    <i class="fas fa-sync-alt"></i> Подвести итоги недели
                </a>
            </div>
        @endcan
    </div>

@endsection
