<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
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
        '2' => 'Отклонена'
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function getCategory(){
        return $this->categories[$this->category];
    }

    public function getStatus(){
        return $this->statuses[$this->status];
    }

}
