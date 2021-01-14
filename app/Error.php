<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{

    protected $casts = [
        'data' => 'array',
        'post' => 'array',
    ];

}
