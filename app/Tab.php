<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model{

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
    ];

    protected $fillable = [
        'convoy_title',
        'lead_id',
//        'description',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function lead(){
        return $this->belongsTo('App\Member');
    }

}
