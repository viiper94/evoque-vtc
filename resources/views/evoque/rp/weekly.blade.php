@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 members-table">
        @include('layout.alert')
        <h2 class="mt-3 text-center text-primary">Итоги рейтинговых перевозок за 10 дней</h2>
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Ник в игре</th>
                    <th>Пройденное расстояние</th>
                    <th>Бонус</th>
                    <th>Тоннаж</th>
                    <th>Кол-во грузов</th>
                    <th>Ступень в ETS2</th>
                    <th>Ступень в ATS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats as $stat)
                    @if($stat->member && $stat->sum_distance > 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @can('updateRpStats', \App\Member::class)
                                    <a href="{{ route('evoque.admin.members.edit', $stat->member->id) }}" class="ml-3"><b>{{ $stat->member->nickname }}</b></a>
                                @else
                                    <b>{{ $stat->member->nickname }}</b>
                                @endcan
                            </td>
                            <td>{{ $stat->sum_distance }} км</td>
                            <td>{{ $stat->sum_bonus }} км</td>
                            <td>{{ $stat->sum_weight }} т</td>
                            <td>{{ $stat->sum_quantity }}</td>
                            @php
                                $old_ets2 = $stat->getStage($stat->distance_total);
                                $new_ets2 = $stat->getStage($stat->distance + $stat->bonus + $stat->distance_total);
                                $old_ats = $stat->getStage($stat->ats_distance_total, 'ats');
                                $new_ats = $stat->getStage($stat->ats_distance + $stat->ats_bonus + $stat->ats_distance_total, 'ats');
                            @endphp
                            <td @class(['text-success font-weight-bold' => $old_ets2 !== $new_ets2])>
                                {{ $old_ets2 }} <i class="fas fa-arrow-right"></i> {{ $new_ets2 }}
                            </td>
                            <td @class(['text-success font-weight-bold' => $old_ats !== $new_ats])>
                                {{ $old_ats }} <i class="fas fa-arrow-right"></i> {{ $new_ats }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        @can('resetStats', \App\RpReport::class)
            <div class="row justify-content-center mb-5">
                <a href="{{ route('evoque.rp.results.create') }}" class="btn btn-outline-warning ml-3 mt-3 btn-lg"
                    onclick="return confirm('Вы уверены?')">
                    <i class="fas fa-sync-alt"></i> Обнулить результаты за 10 дней
                </a>
            </div>
        @endcan
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
    </div>

@endsection
