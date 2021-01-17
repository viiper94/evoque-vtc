<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model{

    protected $table = 'recruitment';

    protected $casts = [
        'have_mic' => 'boolean',
        'have_ts3' => 'boolean',
        'have_ats' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'tmp_join_date'
    ];

    protected $fillable = [
        'name',
        'nickname',
        'age',
        'hours_played',
        'vk_link',
        'steam_link',
        'tmp_link',
        'have_mic',
        'have_ts3',
        'have_ats',
        'tmp_join_date'
    ];

    public $statuses = [
        '0' => 'Новая',
        '1' => 'Принята',
        '2' => 'Отклонена'
    ];

    public function getStatus(){
        return $this->statuses[$this->status];
    }

}
