<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpStats extends Model{

    public static $stages = [
        'ets2' => [
            20000 => '3 балла',
            40000 => 'Раскраска с любой страной (на ваш выбор) + 4 балла',
            75000 => 'Любая раскраска (на ваш выбор) + 5 баллов',
            120000 => 'Schwarzmüller Trailer Pack, National Window Flags, High Power Cargo Pack,
                    Michelin Fan Pack, Raven Truck Design Pack, HS-Schoch Tuning Pack + 6 баллов',
            200000 => 'Going East!, Mighty Griffin Tuning Pack, Cabin Accessories,
                    Wheel Tuning Pack, Heavy Cargo Pack, XF Tuning Pack, Special Transport, Krone Trailer Pack,
                    Actros Tuning Pack, FH Tuning Pack + 7 баллов',
            320000 => 'Scandinavia, Vive la France !, Italia + 8 баллов',
            450000 => 'Beyond the Baltic Sea, Road to the Black Sea + 9 баллов',
            700000 => 'Любая игра или DLC в Steam стоимостью до 1000 рублей + 10 баллов',
        ],
        'ats' => [
            25000 => '3 балла',
            60000 => 'Любая раскраска (на ваш выбор), Steering Creations Pack + 4 балла',
            110000 => 'Wheel Tuning Pack + 5 баллов',
            170000 => 'Heavy Cargo Pack, Special Transport, Forest Machinery + 6 баллов',
            240000 => 'New Mexico, Oregon + 7 баллов',
            450000 => 'Washington, Utah, Idaho + 8 баллов',
            700000 => 'Любая игра или DLC в Steam стоимостью до 1000 рублей + 9 баллов',
        ],
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

    public function getStage(){
        $stages = array_keys(self::$stages[$this->game]);
        foreach($stages as $index => $km){
            if($this->distance_total >= $km && $this->distance_total < $stages[$index+1]) return $index+1;
        }
        return 0;
    }

}
