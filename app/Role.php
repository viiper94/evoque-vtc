<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    public $fillable = ['group', 'title'];

    public $casts = [
        'admin' => 'boolean',
        'manage_members' => 'boolean',
        'manage_convoys' => 'boolean',
        'manage_table' => 'boolean',
        'manage_rp' => 'boolean',
    ];

    public function members(){
        return $this->belongsToMany('App\Member', 'role_member');
    }

}
