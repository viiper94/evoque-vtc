<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Steam extends Model{

    protected $key;

    public function __construct(array $attributes = []){
        $this->key = env('STEAM_API_KEY');
        parent::__construct($attributes);
    }

    public function getPlayerData($steamid64){
        $json = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$this->key.'&steamids='.$steamid64));
        if(count($json->response->players) > 0){
            return collect($json->response->players[0]);
        }else{
            return false;
        }
    }

}
