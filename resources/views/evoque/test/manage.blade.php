@extends('layout.index')

@section('title')
    Управление вопросами к тесту | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container py-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Управление вопросами к тесту</h2>
        <h5 class="text-center">
            @can('create', \App\TestQuestion::class)
                <a href="{{ route('evoque.test.add') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Добавить вопрос
                </a>
            @endcan
            <a href="{{ route('evoque.test') }}" class="btn btn-sm btn-outline-info">
                К тесту
            </a>
        </h5>
        @if(count($questions) > 0)
            @foreach($questions as $question)
                <div class="card card-dark mb-3 text-shadow-m">
                    <div class="card-header row mx-0">
                        <h5 class="mb-0 px-0 col">
                            @can('update', \App\TestQuestion::class)
                                <a href="{{ route('evoque.test.edit', $question->id) }}">{{ $question->question }}</a>
                            @else
                                {{ $question->question }}
                            @endcan
                        </h5>
                        @can('accessToEditPage', \App\TestQuestion::class)
                            <div class="dropdown dropdown-dark col-auto px-0 dropleft">
                                <button class="btn dropdown-toggle no-arrow py-0" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu text-shadow-m" aria-labelledby="dropdownMenuButton">
                                    @can('update', \App\TestQuestion::class)
                                        <a href="{{ route('evoque.test.edit', $question->id) }}" class="dropdown-item"><i class="fas fa-edit"></i> Редактировать</a>
                                    @endcan
                                    @can('delete', \App\TestQuestion::class)
                                        <a href="{{ route('evoque.test.delete', $question->id) }}"
                                           onclick="return confirm('Удалить вопрос?')" class="dropdown-item"><i class="fas fa-trash"></i> Удалить</a>
                                    @endcan
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body row">
                        <div class="col-auto text-center">
                            @can('update', \App\TestQuestion::class)
                                <a class="d-block" @if($question->sort > 1) href="{{ route('evoque.test.sort', [$question->id, 'up']) }}" @endif>
                                    <i class="fas fa-chevron-up"></i>
                                </a>
                            @endcan
                            <h3>{{ $question->sort }}</h3>
                            @can('update', \App\TestQuestion::class)
                                <a href="{{ route('evoque.test.sort', [$question->id, 'down']) }}" class="d-block">
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                            @endcan
                        </div>
                        <div class="col">
                            @foreach($question->answers as $key => $answer)
                                <p class="mb-0 @if($question->correct == $key)text-success @endif">{{ $answer }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h5 class="mt-5 mb-3 text-center">Еще нет вопросов</h5>
        @endif

    </div>

@endsection
