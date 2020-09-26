<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MembersController extends Controller{

    public function index(){
        if(Auth::guest()) abort(403);
        return view('evoque.members.index', [
            'roles' => Role::with(['members', 'members.user', 'members.role' => function($query){
                $query->where('visible', '1');
            }])->where('visible', 1)->get()->groupBy('group')
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        if($request->post()){
            $this->validate($request, [
                'nickname' => 'required|string',
                'join_date' => 'required|date_format:d.m.Y',
                'convoys' => 'required|numeric',
                'scores' => 'required|numeric',
                'money' => 'required|numeric',
                'vacations' => 'required|numeric',
                'plate' => 'required|url',
            ]);
            $member = Member::findOrFail($id);
            $member->fill($request->post());
            $member->visible = $request->input('visible') === 'on';
            $member->join_date = Carbon::parse($request->input('join_date'))->format('Y-m-d');
            $member->on_vacation_till = $request->input('on_vacation_till') ? Carbon::parse($request->input('on_vacation_till'))->format('Y-m-d') : null;
            $member->role()->detach();
            foreach($request->input('roles') as $role){
                $member->role()->attach($role);
            }
            return $member->save() ?
                redirect()->route('evoque.members')->with(['success' => 'Сотрудник успешно отредактирован!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.members.edit', [
            'member' => Member::with('role', 'user')->where('id', $id)->first(),
            'roles' => Role::all()
        ]);
    }

}
