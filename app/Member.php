<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Member extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    public $casts = [
        'visible' => 'boolean',
        'sort' => 'boolean',
    ];

    protected $dates = [
        'on_vacation_till',
        'join_date',
        'created_at',
        'updated_at'
    ];

    public $fillable = [
        'user_id',
        'nickname',
        'join_date',
        'convoys',
        'scores',
        'money',
        'vacations',
        'on_vacation_till',
        'plate',
        'sort',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function role(){
        return $this->belongsToMany('App\Role', 'role_member');
    }

    public function stat(){
        return $this->hasOne('App\RpStats');
    }

    public function getPlace(){
        if(isset($this->user->city) || isset($this->user->country)){
            return implode('/', array_filter([$this->user->city, $this->user->country]));
        }
        return '–';
    }

    public function topRole(){
        $index = 99;
        foreach($this->role as $role){
            if ($role->id < $index) $index = $role->id;
        }
        return $index;
    }

    public function onVacation(){
        return isset($this->on_vacation_till) && $this->on_vacation_till->isFuture();
    }

}
