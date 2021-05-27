<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model{

    protected $casts = [
        'correct' => 'boolean'
    ];

    public function question(){
        return $this->belongsTo('App\TestQuestion', 'question_id', 'id');
    }

}
