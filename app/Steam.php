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

    private function getUsersGames($steamid64){
        $json = json_decode(file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.$this->key.'&steamid='.$steamid64.'&format=json'));
        if(property_exists($json->response, 'games') && count($json->response->games) > 0){
            return collect($json->response->games);
        }else{
            return false;
        }
    }

    public function getSCSGamesData($steamid64){
        $games = $this->getUsersGames($steamid64);
        $ets2 = null;
        $ats = null;
        if(!$games) return ['ets2' => null, 'ats' => null];
        foreach($games as $game){
            if($game->appid == '227300'){
                $ets2 = floor(intval($game->playtime_forever)/60);
            }
            if($game->appid == '270880'){
                $ats = floor(intval($game->playtime_forever)/60);
            }
        }
        return ['ets2' => $ets2, 'ats' => $ats];
    }

}
