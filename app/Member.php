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

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function role(){
        return $this->belongsToMany('App\Role', 'role_member');
    }

}
