<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;

class RolesController extends Controller{

    public function roles(){
        return view('evoque.admin.roles.index', [
            'roles' => Role::with('members')->get()
        ]);
    }

    public function edit(Request $request, $id){
        $role = Role::findOrFail($id);
        if($request->post() && $id){
            $this->validate($request, [
                'title' => 'required|string',
                'group' => 'required|string'
            ]);
            $role->fill($request->post());
            $role->admin = $request->input('admin') == 'on';
            $role->manage_members = $request->input('manage_members') == 'on';
            $role->manage_convoys = $request->input('manage_convoys') == 'on';
            $role->manage_table = $request->input('manage_table') == 'on';
            $role->manage_rp = $request->input('manage_rp') == 'on';
            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Роль успешно отредактирована!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.admin.roles.edit', [
            'role' => $role
        ]);
    }

    public function add(Request $request){
        $role = new Role;
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'group' => 'required|string'
            ]);
            $role->fill($request->post());
            $role->admin = $request->input('admin') == 'on';
            $role->manage_members = $request->input('manage_members') == 'on';
            $role->manage_convoys = $request->input('manage_convoys') == 'on';
            $role->manage_table = $request->input('manage_table') == 'on';
            $role->manage_rp = $request->input('manage_rp') == 'on';
            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Роль успешно добавлена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.admin.roles.edit', [
            'role' => $role
        ]);
    }

    public function delete(Request $request, $id){
        if($id){
            $role = Role::with('members')->where('id', $id)->first();
            $role->members()->detach();
            return $role->delete() ? redirect()->route('evoque.admin.roles')->with(['success' => 'Роль удалена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->route('evoque.admin.roles');
    }

}