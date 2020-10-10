<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RpReport extends Model{

    protected $casts = [
        'images' => 'array',
        'status' => 'boolean'
    ];

    protected $fillable = [
        'note',
        'game',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

}
