<?php

namespace App\Http\Controllers;

use App\Member;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MembersController extends Controller{

    public function index(){
        return view('evoque.members.index', [
            'roles' => Role::with(['members', 'members.user', 'members.role'])->get()->groupBy('group')
        ]);
    }

    public function edit(Request $request, $id){
        if(Gate::denies('manage_members')) abort(403);
        if($request->post()){
            $member = Member::findOrFail($id);
            $member->fill($request->post());
            $member->visible = $request->input('visible') === 'on';
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
