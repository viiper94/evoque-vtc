<?php

namespace App\Http\Controllers;

use App\Member;
use App\Rules\NullPlate;
use App\Rules\UniquePlate;
use App\Steam;
use App\User;
use Carbon\Carbon;
use \Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller{

    public function profile(Request $request, $id = null){
        if(!Auth::user()?->member) abort(404);
        if($id && Auth::user()->can('view', User::class)){
            $user = User::findOrFail($id);
        }else{
            $user = Auth::user();
        };
        return view('evoque.profile.index', [
            'user' => $user
        ]);
    }

    public function edit(Request $request){
        if(!Auth::user()?->member) abort(404);
        if($request->post()){
            $this->validate($request, [
                'name' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
                'email' => 'nullable|email',
                'birth_date' => 'required|date_format:d.m.Y',
                'plate' => [new UniquePlate(), new NullPlate(), 'not_in:000,00,0'],
            ]);
            $user = Auth::user();
            $user->fill($request->post());
            $user->birth_date = Carbon::parse($request->input('birth_date'))->format('Y-m-d');
            if($user->member){
                $user->member->plate = $request->input('plate');
                $user->member->save();
            }
            return $user->save() ?
                redirect()->route('evoque.profile')->with(['success' => 'Профиль успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function updateAvatar(){
        if(!Auth::user()?->member) abort(404);

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

    public function resetAvatar(Request $request, $id){
        $user = User::findOrFail($id);
        $this->authorize('resetAvatar', $user);
        $user->image = URL::to('/').'/images/users/evoque.jpg';
        return  $user->save() ?
            redirect()->back()->with(['success' => 'Аватар успешно сброшен!']) :
            redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

    public function checkPlate(Request $request){
        if(!$request->ajax() && (!Auth::user()?->member)) abort(404);

        $value = $request->input('value');
        if(strlen($value) === 1) $value = '00'.$value;
        if(strlen($value) === 2) $value = '0'.$value;
        $match = Member::where([
            ['plate', '=', $value],
            ['user_id', '!=', Auth::id()]
        ])->first();
        return response()->json([
            'data' => [
                'isFree' => !$match,
                'value' => $value
            ]
        ]);
    }

}
