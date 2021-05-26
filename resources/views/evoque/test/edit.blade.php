@extends('layout.index')

@section('title')
    Редактирование вопроса | @lang('general.vtc_evoque')
@endsection

@section('content')

    <div class="container pt-5 pb-5">
        @include('layout.alert')
        <h2 class="mt-3 mb-3 text-primary text-center">Редактирование вопроса</h2>
        <form method="post">
            @csrf
            <div class="form-group col-md col-12">
                <h5>Вопрос</h5>
                <input type="text" class="form-control form-control-lg" id="question" name="question"
                       value="{{ old('question') ?? $question->question }}" required>
                @if($errors->has('question'))
                    <small class="form-text">{{ $errors->first('question') }}</small>
                @endif
            </div>
            <div class="row col mt-5">
                <h5 class="col-12">
                    Ответы
                    <a href="{{ route('evoque.test.add') }}" class="btn btn-sm text-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </h5>
                @foreach($question->answers as $key => $answer)
                    <div class="form-group col-sm-9">
                        <input type="text" class="form-control mb-2 " id="answer-{{ $key }}" name="answers[]"
                               value="{{ old('answers['.$key.']') ?? $question->question }}" required>
                    </div>
                    <div class="custom-control custom-radio col-sm-3 pl-5">
                        <input type="radio" id="correct-{{ $key }}" name="correct" value="{{ $key }}"
                               class="custom-control-input" @if($question->correct == $key)checked @endif required>
                        <label class="custom-control-label" for="correct-{{ $key }}">Правильный ответ</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="ml-3 btn btn-outline-primary col-auto">Сохранить</button>
        </form>
    </div>

@endsection
