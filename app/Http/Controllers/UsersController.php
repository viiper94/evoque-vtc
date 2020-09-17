<?php

namespace App\Http\Controllers;

use App\Member;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use TruckersMP\APIClient\Client;

class UsersController extends Controller{

    public function index(){
        if(Gate::denies('manage_members')) abort(403);
        return view('evoque.users', [
            'users' => User::with('member')->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function setAsMember(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        $user = User::findOrFail($id);
        $member = new Member();
        $member->user_id = $user->id;
        $member->join_date = Carbon::now();
        $tmp = new Client();
        $tmp_info= $tmp->player($user->steamid64)->get();
        $member->nickname = str_replace('[EVOQUE] ', '', $tmp_info->getName());
        if($member->save()){
            $member->role()->attach('14');
            $member->update();
            return redirect()->route('evoque.admin.users')->with(['success' => 'Сотрудник успешно добавлен!']);
        }
        return redirect()->back()->withErrors(['Возникла ошибка =(']);
    }

}
