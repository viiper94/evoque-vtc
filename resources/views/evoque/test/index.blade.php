@extends('layout.index')

@section('title')
    Тест на знание ВТК | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Тест на знание ВТК</h2>
        <h5 class="text-center">
            @can('view', \App\TestResult::class)
                <a href="{{ route('evoque.test.results') }}" class="btn btn-sm btn-outline-success my-1">
                    <i class="fas fa-poll"></i> Результаты
                </a>
            @endcan
            @can('accessToEditPage', \App\TestQuestion::class)
                <a href="{{ route('evoque.test.manage') }}" class="btn btn-sm btn-outline-info my-1">
                    <i class="fas fa-edit"></i> Редактировать вопросы
                </a>
            @endcan
        </h5>
        @include('evoque.test.inc.'.$view)
    </div>

@endsection
