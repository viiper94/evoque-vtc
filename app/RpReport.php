<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpReport extends Model{

    protected $casts = [
        'images' => 'array'
    ];

    protected $fillable = [
        'note',
        'game',
    ];

}
