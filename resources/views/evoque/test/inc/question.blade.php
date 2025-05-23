<div class="row flex-column align-items-center my-3 mx-0">
    <div class="btn-group btn-group-sm flex-wrap" role="group">
        @for($i = 1; $i <= $count; $i++)
            <a href="{{ route('evoque.test', $i) }}"
               class="question-number btn {{ $question->getBtnClass($i, $results) }}">{{ $i }}
            </a>
        @endfor
    </div>
    <h4 class="my-5 text-center">{{ $question->question }}</h4>
    <form method="post" class="row align-items-center flex-column" action="{{ route('evoque.test', $question->isLast() ? null : $question->sort + 1) }}">
        @csrf
        <div class="col btn-group-toggle text-center" data-toggle="buttons">
            @foreach($question->answers as $key => $answer)
                <label @class(['my-1 btn',
                            'btn-success' => $question->isAnswered($results) && $question->isCorrectAnswer($results, $key),
                            'btn-danger' => $question->isAnswered($results) && $question->isWrongAnswer($results, $key),
                            'disabled' => $question->isAnswered($results),
                            'btn-secondary' => !$question->isCorrectAnswer($results, $key) && !$question->isWrongAnswer($results, $key)
                        ])>
                    <input type="radio" name="answer" value="{{ $key }}" id="{{ $key }}"> {{ $answer }}
                </label>
            @endforeach
        </div>
        <input type="hidden" name="sort" value="{{ $question->sort }}">
        @if($question->isAnswered($results))
            <a class="btn btn-lg btn-primary my-5" href="{{ route('evoque.test', $question->isLast() ? null : $question->sort + 1) }}">Далее <i class="fas fa-angle-right"></i></a>
        @else
            <button type="submit" class="btn btn-lg btn-primary my-5">Далее <i class="fas fa-angle-right"></i></button>
        @endif
    </form>
</div>
