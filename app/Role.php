<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    public $fillable = [
        'group',
        'title',
        'description'
    ];

    public function members(){
        return $this->belongsToMany('App\Member', 'role_member');
    }

}
