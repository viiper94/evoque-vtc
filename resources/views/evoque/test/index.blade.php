@extends('layout.index')

@section('title')
    Тест на знание ВТК | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Тест на знание ВТК</h2>
        <h5 class="text-center">
            <a href="{{ route('evoque.test.edit') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i> Редактировать вопросы
            </a>
        </h5>
    </div>

@endsection
