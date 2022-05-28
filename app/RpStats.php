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
        'bonus',
        'weight',
        'distance_total',
        'weight_total',
        'quantity',
        'quantity_total',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function getStage($km = null, $game = null){
        $stages = RpReward::select(['stage', 'km'])->whereGame($game ?? $this->game)->get()->keyBy('stage');
        $distance = $km ?? $this->distance_total;
        foreach($stages as $stage => $reward){
            if($distance >= $reward->km && (!isset($stages[$stage+1]) || $distance < $stages[$stage+1]->km)) return $stage;
        }
        return 0;
    }

}
