<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'vacation_till'
    ];

    protected $fillable = [

    ];

    public $categories = [
        '1' => 'отпуск',
        '2' => 'смену номера',
        '3' => 'сброс статистики рейтинговых перевозок',
        '4' => 'смену никнейма',
        '5' => 'увольнение',
    ];

    public function member(){
        return $this->belongsTo('App\Member');
    }

    public function getCategory(){
        return $this->categories[$this->category];
    }

}
