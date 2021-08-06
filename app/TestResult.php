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

    public function member(){
        return $this->belongsTo('App\Member', 'member_id', 'id');
    }

}
