<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model{

    protected $fillable = [
        'question',
        'answers',
        'correct',
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    public function __construct(array $attributes = []){
        $this->answers = [
            0 => '',
            1 => '',
        ];

        parent::__construct($attributes);
    }

    public function isLast() :bool{
        $next = TestQuestion::whereSort($this->sort + 1)->first();
        return !$next;
    }

    public static function getFirstQuestionId(){
        return TestQuestion::select('sort')->orderBy('sort', 'asc')->first();
    }

    public static function resort(){
        $questions = TestQuestion::orderBy('sort')->get();
        foreach($questions as $key => $question){
            $question->sort = $key + 1;
            $question->save();
        }
        return true;
    }

    public function getBtnClass($i, $results) :string{
        $class = 'btn-';
        if($i !== $this->sort){
            $class .= 'outline-';
        }
        if($results->has($i)){
            $class .= $results[$i]->correct ? 'success' : 'danger';
        }else{
            $class .= 'secondary';
        }
        return $class;
    }

    public function isAnswered($results) :bool{
        return isset($results[$this->sort]);
    }

    public function isCorrectAnswer($results, $key) :bool{
        if($this->isAnswered($results)) return $this->correct === $key;
        else return false;
    }

    public function isWrongAnswer($results, $key) :bool{
        if($this->isAnswered($results)) return !$results[$this->sort]?->correct && $results[$this->sort]?->answer == $key;
        else return false;
    }

}
