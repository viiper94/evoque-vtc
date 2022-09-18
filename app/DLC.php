<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DLC extends Model{

    protected $fillable = [
        'title', 'steam_link', 'game'
    ];

    protected $table = 'dlc';

}
