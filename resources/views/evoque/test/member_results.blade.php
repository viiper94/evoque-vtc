@extends('layout.index')

@section('title')
    Результаты сотрудника {{ $member->nickname }} | @lang('general.vtc_evoque')
@endsection

@section('content')
{{--    @dd($results)--}}
    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Результаты сотрудника {{ $member->nickname }}</h2>
        <h5 class="text-center">
            @can('view', \App\TestResult::class)
                <a href="{{ route('evoque.test.results') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-poll"></i> Результаты всех
                </a>
            @endcan
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
                    <th scope="col">Вопрос</th>
                    <th scope="col">Правильный ответ</th>
                    <th scope="col">Ответ {{ $member->nickname }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($results as $result)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $result->question->question }}</td>
                        <td>{{ $result->question->answers[$result->question->correct] }}</td>
                        <td>
                            <b @if($result->question->correct === $result->answer)class="text-success"
                               @else class="text-danger" @endif>
                                {{ $result->question->answers[$result->answer] }}</b>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
