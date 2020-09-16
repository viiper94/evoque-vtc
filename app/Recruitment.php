<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model{

    protected $table = 'recruitment';

    protected $casts = [
        'have_mic' => 'boolean',
        'have_ts3' => 'boolean',
        'have_ats' => 'boolean',
    ];

    protected $fillable = [
        
    ];

}
