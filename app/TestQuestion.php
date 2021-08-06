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

}
