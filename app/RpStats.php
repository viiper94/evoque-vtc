<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpStats extends Model{

    public static $bonus = [
        '< 14 т' => 'x0',
        '15 - 19 т' => 'x0.1',
        '20 - 25 т' => 'x0.3',
        '26 - 32 т' => 'x0.5',
        '> 33 т' => 'x0.7'
    ];

    protected $fillable = [
        'game',
        'distance',
        'level',
        'level_promods',
        'bonus',
        'weight',
        'distance_total',
        'weight_total',
        'quantity',
        'quantity_total',
    ];

    public function rewards(){
        return $this->hasMany(RpReward::class, 'game', 'game');
    }

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function getStage($km = null, $game = 'ets2'){
        $distance = $km ?? $this->distance_total;
        $rewards = RpReward::whereGame($game)->get()->keyBy('stage');
        foreach($rewards as $stage => $reward){
            if($distance >= $reward->km && (!isset($rewards[$stage+1]) || $distance < $rewards[$stage+1]->km)) return $stage;
        }
        return 0;
    }

}
