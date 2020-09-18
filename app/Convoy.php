<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Convoy extends Model{

    protected $fillable = [
        'title',
        'start_time',
        'start',
        'rest',
        'finish',
        'server',
        'communication',
        'lead',
        'truck',
        'truck_tuning',
        'truck_paint',
        'trailer',
        'trailer_tuning',
        'trailer_paint',
        'trailer_image',
        'cargo',
        'route',
    ];

    protected $casts = [
        'start_time' => 'dateTime'
    ];

    public function isUpcoming(){
        $now = Carbon::now();
        return $now->subMinutes(45)->lessThan($this->start_time);
    }

}
