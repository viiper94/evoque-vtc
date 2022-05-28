<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpReward extends Model{
    use HasFactory;

    public $fillable = [
        'stage', 'km', 'reward', 'game'
    ];

}
