@extends('layout.index')

@section('title')
    Статистика рейтинговых перевозок | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container-fluid pt-5 members-table">
        @include('layout.alert')
        <h2 class="pt-3 text-center text-primary">Статистика рейтинговых перевозок</h2>
        <div class="table-responsive mb-5">
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

                </tbody>
            </table>
        </div>
    </div>

@endsection
