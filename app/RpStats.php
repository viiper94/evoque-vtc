<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpStats extends Model{

    protected $fillable = [
        'game',
        'distance',
        'level',
        'bonus',
        'weight'
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

}
