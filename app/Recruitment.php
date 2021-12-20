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
        'vk_link',
        'tmp_link',
        'have_mic',
        'discord_name',
    ];

    public $statuses = [
        '0' => 'Новая',
        '1' => 'Принята',
        '2' => 'Отклонена',
        '3' => 'В работе',
    ];

    public function getStatus(){
        return $this->statuses[$this->status];
    }

}
