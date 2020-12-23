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
                'plate' => 'nullable|regex:/[0-9]{3}/',
            ]);
            $user = Auth::user();
            $user->fill($request->post());
            $user->birth_date = Carbon::parse($request->input('birth_date'))->format('Y-m-d');
            if($user->member){
                $user->member->plate = $request->input('plate');
                $user->member->save();
            }
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
                redirect()->route('evoque.profile.edit')->with(['success' => 'Аватар успешно обновлён!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function checkPlate(Request $request){
        if(!$request->ajax() && Auth::guest()) abort(404);

        $value = $request->input('value');
        if(strlen($value) === 1) $value = '00'.$value;
        if(strlen($value) === 2) $value = '0'.$value;
        $match = Member::where([
            ['plate', '=', $value],
            ['user_id', '!=', Auth::id()]
        ])->first();
        return response()->json([
            'data' => [
                'isFree' => $match ? false : true,
                'value' => $value
            ]
        ]);
    }

}
