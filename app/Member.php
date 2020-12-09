<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Member extends Model implements Auditable{

    use \OwenIt\Auditing\Auditable;

    protected $casts = [
        'visible' => 'boolean',
        'sort' => 'boolean',
    ];

    protected $dates = [
        'on_vacation_till',
        'trainee_until',
        'join_date',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'nickname',
        'join_date',
        'convoys',
        'trainee_convoys',
        'scores',
//        'money',
        'vacations',
        'on_vacation_till',
        'trainee_until',
        'plate',
        'sort',
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function role(){
        return $this->belongsToMany('App\Role', 'role_member');
    }

    public function stats(){
        return $this->hasMany('App\RpStats');
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
        return isset($this->on_vacation_till) && ($this->on_vacation_till->isFuture() || $this->on_vacation_till->isToday());
    }

    public function isTrainee(){
        return $this->topRole() === 14;
    }

    public function isTraineeExpired(){
        $trainee_end = $this->trainee_until ?? $this->join_date->addDays(10);
        return $trainee_end->lte(Carbon::today());
    }

    public function checkRoles(){
        foreach($this->role as $role){
            if(($role->max_scores && $this->scores > $role->max_scores) ||
                ($role->min_scores && $this->scores < $role->min_scores)){
                $new_role = Role::where('min_scores', '<=', $this->scores)->orWhere('min_scores', 'null')
                    ->where('max_scores', '>=', $this->scores)->orWhere('max_scores', 'null')->first();
                $this->role()->detach($role->id);
                $this->role()->attach($new_role->id);
            }
        }
    }

}
