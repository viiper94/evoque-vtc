<?php

namespace App;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Application extends Model{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'new_rp_profile' => 'array',
        'vacation_till' => 'array',
    ];

    public $categories = [
        '1' => 'отпуск',
        '2' => 'смену номера',
        '3' => 'смену уровня в рейтинговых перевозках',
        '4' => 'смену ника',
        '5' => 'увольнение',
    ];

    public $statuses = [
        '0' => 'Новая',
        '1' => 'Принята',
        '2' => 'Отклонена',
        '3' => 'В работе',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function comments(){
        return $this->hasMany('App\Comment', 'instance_id')->where('instance', 'App\Application');
    }

    public function getCategory(){
        return $this->categories[$this->category];
    }

    public function getStatus(){
        return trans('status.'.Status::from($this->status)->name);
    }

    public function isClosed(){
        return $this->status == 1 || $this->status == 2;
    }

}
