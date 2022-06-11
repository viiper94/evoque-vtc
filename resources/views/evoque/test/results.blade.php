@extends('layout.index')

@section('title')
    Результаты тестирования | @lang('general.vtc_evoque')
@endsection

@section('content')
<div class="container py-5">
    @include('layout.alert')
    <h2 class="mt-3 mb-3 text-primary text-center">Результаты тестирования</h2>
    <h5 class="text-center">
        <a href="{{ route('evoque.test') }}" class="btn btn-sm btn-outline-success">
            <i class="fas fa-poll"></i> К тесту
        </a>
        @can('accessToEditPage', \App\TestQuestion::class)
            <a href="{{ route('evoque.test.edit') }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-edit"></i> Редактировать вопросы
            </a>
        @endcan
    </h5>
    <div class="table-responsive mb-3">
        <table class="table table-dark table-hover roles-table text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Сотрудник</th>
                <th scope="col">Вопросов пройдено</th>
                <th scope="col">Правильных ответов</th>
                <th scope="col">Последний ответ дан</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $nickname => $result)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td><a href="{{ route('evoque.test.results.member', $result['id']) }}">{{ $nickname }}</a></td>
                    <td>{{ $result['count'] }}/{{ $total }}</td>
                    <td>{{ $result['correct'] }} <span class="text-muted">({{ floor($result['correct']/$result['count']*100) }}%)</span></td>
                    <td>{{ $result['last'] }}</td>
                    <td>
                        @can('delete', \App\TestResult::class)
                            <a href="{{ route('evoque.test.results.delete', $result['id']) }}" onclick="return confirm('Удалить результаты?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
