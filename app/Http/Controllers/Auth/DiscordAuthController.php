<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller{

    public function redirect(){
        return Socialite::driver('discord')->redirect();
    }

    public function handle(){
        $discord_user = Socialite::driver('discord')->user();

        $user = User::find(Auth::id());
        $user->discord_id = $discord_user->getId();
        $user->discord_name = $discord_user->getNickname();
        return $user->save() ?
            redirect()->route('evoque.profile.edit')->with(['success' => 'Аккаунт Discord успешно добавлен!']) :
            redirect()->route('evoque.profile.edit')->withErrors(['Возникла ошибка =(']);
    }

}
