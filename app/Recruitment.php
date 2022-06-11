<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model{

    protected $table = 'recruitment';

    protected $casts = [
        'have_mic' => 'boolean',
        'have_ts3' => 'boolean',
        'have_ats' => 'boolean',
        'status' => 'integer',
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

    public function comments(){
        return $this->hasMany('App\Comment', 'instance_id')->where('instance', 'App\Recruitment');
    }

    public function getStatus(){
        return $this->statuses[$this->status];
    }

    public function isClosed(){
        return $this->status == 1 || $this->status == 2;
    }

}
