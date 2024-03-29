<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller{

    public function roles(){
        $this->authorize('view', Role::class);
        return view('evoque.roles.index', [
            'roles' => Role::withCount('members')->get()
        ]);
    }

    public function editPermissions(Request $request, $id){
        $role = Role::findOrFail($id);
        $this->authorize('updatePermissions', $role);
        if($request->post() && $id !== false){
            $role->admin = $request->input('admin') == 'on';
            foreach(Role::$permission_list as $list){
                foreach($list as $item){
                    $role->$item = $request->input($item) == 'on';
                }
            }
            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Права роли успешно отредактированы!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->back();
    }

    public function edit(Request $request, $id = null){
        $this->authorize(($id !== null ? 'update' : 'create'), Role::class);
        $role = $id !== null ? Role::findOrFail($id) : new Role();
        if($request->post()){
            $this->validate($request, [
                'title' => 'required|string',
                'group' => 'required|string',
                'min_scores' => 'nullable|numeric',
                'max_scores' => 'nullable|numeric',
            ]);
            $role->fill($request->post());
            $role->visible = $request->input('visible') == 'on';
            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Роль успешно '.($id !== false ? 'отредактирована!' : 'добавлена!')]) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.roles.edit', [
            'role' => $role
        ]);
    }

    public function delete(Request $request, $id){
        $this->authorize('delete', Role::class);
        if($id){
            $role = Role::with('members')->where('id', $id)->first();
            $role->members()->detach();
            return $role->delete() ? redirect()->route('evoque.admin.roles')->with(['success' => 'Роль удалена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->route('evoque.admin.roles');
    }

}
