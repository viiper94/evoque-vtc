<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model{

    public $casts = [
        'visible' => 'boolean',
//        'join_date' => 'date:j M, Y',

    ];

    protected $dates = [
        'join_date',
        'created_at',
        'updated_at'
    ];

    public $fillable = [
        'nickname',
        'role_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function role(){
        return $this->belongsToMany('App\Role', 'role_member');
    }

}
