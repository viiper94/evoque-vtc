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

    public function isLast(){
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

}
