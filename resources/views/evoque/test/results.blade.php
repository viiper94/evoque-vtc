@extends('layout.index')

@section('title')
    Результаты тестирования | @lang('general.vtc_evoque')
@endsection

@section('content')
{{--    @dd($results)--}}
<div class="container py-5">
    @include('layout.alert')
    <h2 class="mt-3 mb-3 text-primary text-center">Результаты тестирования</h2>
    <div class="table-responsive mb-3">
        <table class="table table-dark table-hover roles-table text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Сотрудник</th>
                <th scope="col">Тест пройден</th>
                <th scope="col">Правильных ответов</th>
                <th scope="col">Последний ответ дан</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $nickname => $result)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $nickname }}</td>
                    <td>@if($result['complete'])<i class="fas fa-check text-success"></i> @else<i class="fas fa-times text-danger"></i> @endif</td>
                    <td>{{ $result['correct'] }}/{{ $result['count'] }}</td>
                    <td>{{ $result['last'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
