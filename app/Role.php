<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    protected $fillable = [
        'group',
        'title',
        'description',
        'min_scores',
        'max_scores',
        'next_role',
        'prev_role',
    ];

    public function members(){
        return $this->belongsToMany('App\Member', 'role_member');
    }

    public function nextRole(){
        return $this->hasOne('App\Role', 'id', 'next_role');
    }

    public function prevRole(){
        return $this->hasOne('App\Role', 'id', 'prev_role');
    }

}
