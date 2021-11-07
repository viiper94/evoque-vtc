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
            <div class="row col mt-5 mx-0 px-0 test-answers">
                <h5 class="col-12">
                    Ответы
                    <a class="btn btn-sm text-primary" id="add_answer" data-index="1" data-target="test-answers">
                        <i class="fas fa-plus"></i>
                    </a>
                </h5>
                @foreach($question->answers as $key => $answer)
                    <div class="answer col-12 row mx-0" id="answer-{{ $key }}">
                        <div class="form-group col-sm-9 col-xs-12 px-0 mb-0 mb-sm-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="answers[]"
                                       value="{{ old('answers['.$key.']') ?? $question->answers[$key] }}" required>
                                @if($loop->iteration > 2)
                                    <div class="input-group-append">
                                        <div class="input-group-text p-0">
                                            <a class="btn btn-sm text-primary remove_answer" data-index="{{ $key }}">
                                                <i class="fas fa-minus text-danger"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="custom-control custom-radio col-sm-3 col-xs-12 pl-4 pl-sm-5 pr-0 mb-3">
                            <input type="radio" id="correct-{{ $key }}" name="correct" value="{{ $key }}"
                                   class="custom-control-input" @if($question->correct == $key)checked @endif required>
                            <label class="custom-control-label" for="correct-{{ $key }}">Правильный ответ</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="ml-3 btn btn-outline-primary col-auto">Сохранить</button>
        </form>
    </div>

    <script type="text/html" id="test-answers_template">
        <div class="answer col-12 row" id="answer-%i%">
            <div class="form-group col-sm-9">
                <div class="input-group">
                    <input type="text" class="form-control" name="answers[]" required>
                    <div class="input-group-append">
                        <div class="input-group-text p-0">
                            <a class="btn btn-sm text-primary remove_answer" data-index="%i%">
                                <i class="fas fa-minus text-danger"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-control custom-radio col-sm-3 pl-5">
                <input type="radio" id="correct-%i%" name="correct" value="%i%"
                       class="custom-control-input" required>
                <label class="custom-control-label" for="correct-%i%">Правильный ответ</label>
            </div>
        </div>
    </script>

@endsection
