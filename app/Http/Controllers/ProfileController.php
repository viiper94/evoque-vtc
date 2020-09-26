<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Member;
use App\Role;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller{

    public function profile(Request $request, $id = null){
        if(Auth::guest()) abort(404);
        return view('evoque.profile.index');
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

}
