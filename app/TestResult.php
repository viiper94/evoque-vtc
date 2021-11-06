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

    public static function deleteOldResults(){
        $results = TestResult::with('member')
            ->where('created_at', '<', \Carbon\Carbon::now()->subMonth()->format('Y-m-d H:i'))
            ->get()->groupBy('member.id');
        foreach($results as $member_id => $items){
            TestResult::whereMemberId($member_id)->delete();
        }
        return true;
    }

}
