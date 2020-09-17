<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model{

    public $casts = [
        'visible' => 'boolean',
    ];

    protected $dates = [
        'on_vacation_till',
        'join_date',
        'created_at',
        'updated_at'
    ];

    public $fillable = [
        'nickname',
        'join_date',
        'convoys',
        'scores',
        'money',
        'vacations',
        'on_vacation_till',
        'plate',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function role(){
        return $this->belongsToMany('App\Role', 'role_member');
    }

    public function getPlace(){
        if(isset($this->user->city) || isset($this->user->country)){
            return implode('/', array_filter([$this->user->city, $this->user->country]));
        }
        return '–';
    }

    public function isOwner(){
        foreach($this->role as $role){
            if($role->id == 1) return true;
        }
        return false;
    }

}
