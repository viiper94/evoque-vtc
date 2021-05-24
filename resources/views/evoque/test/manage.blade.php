@extends('layout.index')

@section('title')
    Управление вопросами к тесту | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container py-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Управление вопросами к тесту</h2>
        <h5 class="text-center">
            <a href="{{ route('evoque.test.add') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus"></i> Добавить вопрос
            </a>
        </h5>
        @if(count($questions) > 0)
            @foreach($questions as $question)
                <div class="card card-dark">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $question->question }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($question->answers as $key => $answer)
                            <p class="mb-0 @if($question->correct == $key)text-success @endif">{{ $answer }}</p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <h5 class="mt-5 mb-3 text-center">Еще нет вопросов</h5>
        @endif

    </div>

@endsection
