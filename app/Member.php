<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model{

    public $casts = [
        'visible' => 'boolean'
    ];

    public $fillable = [
        'nickname',
        'role_id',
        'user_id'
    ];

}
