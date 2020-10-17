<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpStats extends Model{

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

}
