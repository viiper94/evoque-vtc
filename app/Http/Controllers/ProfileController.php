<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Member;
use App\Role;
use App\Steam;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Relisoft\Steam\Src\Api\Player;

class ProfileController extends Controller{

    public function profile(Request $request, $id = null){
        if(Auth::guest()) abort(404);
        if($id && Gate::check('manage_members')){
            $user = User::findOrFail($id);
        }else{
            $user = Auth::user();
        };
        return view('evoque.profile.index', [
            'user' => $user
        ]);
    }

    public function edit(Request $request){
        if(Auth::guest()) abort(404);
        if($request->post()){
            $this->validate($request, [
                'name' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
                'birth_date' => 'required|date_format:d.m.Y',
            ]);
            $user = Auth::user();
            $user->fill($request->post());
            $user->birth_date = Carbon::parse($request->input('birth_date'))->format('Y-m-d');
            return $user->save() ?
                redirect()->route('evoque.members')->with(['success' => 'Профиль успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function updateAvatar(){
        if(Auth::guest()) abort(404);

        $client = new Steam();
        $player = $client->getPlayerData(Auth::user()->steamid64);

        if($player){
            $user = User::findOrFail(Auth::user()->id);
            $user->image = $player['avatarfull'];
            return $user->save() ?
                redirect()->route('evoque.profile')->with(['success' => 'Аватар успешно обновлён!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
