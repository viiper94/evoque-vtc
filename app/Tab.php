<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
    ];

    protected $fillable = [
        'convoy_title',
        'lead_id',
        'comment',
//        'description',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function lead(){
        return $this->belongsTo('App\Member');
    }

    public function setCommentAttribute($value){
        $this->attributes['comment'] = str_replace(PHP_EOL, '  '.PHP_EOL, $value);
    }

}
