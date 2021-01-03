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
            'roles' => Role::with('members')->get()
        ]);
    }

    public function editPermissions(Request $request, $id){
        $role = Role::findOrFail($id);
        $this->authorize('updatePermissions', $role);
        if($request->post() && $id !== false){
            $role->admin = $request->input('admin') == 'on';

            $role->manage_members = $request->input('manage_members') == 'on';
            $role->edit_members = $request->input('edit_members') == 'on';
            $role->edit_members_activity = $request->input('edit_members_activity') == 'on';
            $role->edit_members_rp_stats = $request->input('edit_members_rp_stats') == 'on';
            $role->fire_members = $request->input('fire_members') == 'on';
            $role->set_members_activity = $request->input('set_members_activity') == 'on';
            $role->reset_members_activity = $request->input('reset_members_activity') == 'on';
            $role->see_bans = $request->input('see_bans') == 'on';

            $role->manage_applications = $request->input('manage_applications') == 'on';
            $role->view_recruitments = $request->input('view_recruitments') == 'on';
            $role->claim_recruitments = $request->input('claim_recruitments') == 'on';
            $role->delete_recruitments = $request->input('delete_recruitments') == 'on';
            $role->make_applications = $request->input('make_applications') == 'on';
            $role->view_applications = $request->input('view_applications') == 'on';
            $role->claim_applications = $request->input('claim_applications') == 'on';
            $role->delete_applications = $request->input('delete_applications') == 'on';

            $role->manage_convoys = $request->input('manage_convoys') == 'on';
            $role->view_all_convoys = $request->input('view_all_convoys') == 'on';
            $role->book_convoys = $request->input('book_convoys') == 'on';
            $role->quick_book_convoys = $request->input('quick_book_convoys') == 'on';
            $role->add_convoys = $request->input('add_convoys') == 'on';
            $role->edit_convoys = $request->input('edit_convoys') == 'on';
            $role->delete_convoys = $request->input('delete_convoys') == 'on';

            $role->manage_tab = $request->input('manage_tab') == 'on';
            $role->view_tab = $request->input('view_tab') == 'on';
            $role->add_tab = $request->input('add_tab') == 'on';
            $role->edit_tab = $request->input('edit_tab') == 'on';
            $role->accept_tab = $request->input('accept_tab') == 'on';
            $role->delete_tab = $request->input('delete_tab') == 'on';

            $role->manage_rp = $request->input('manage_rp') == 'on';
            $role->add_reports = $request->input('add_reports') == 'on';
            $role->view_all_reports = $request->input('view_all_reports') == 'on';
            $role->delete_own_reports = $request->input('delete_own_reports') == 'on';
            $role->delete_all_reports = $request->input('delete_all_reports') == 'on';
            $role->accept_reports = $request->input('accept_reports') == 'on';
            $role->reset_stats = $request->input('reset_stats') == 'on';

            $role->manage_rules = $request->input('manage_rules') == 'on';
            $role->add_rules = $request->input('add_rules') == 'on';
            $role->edit_rules = $request->input('edit_rules') == 'on';
            $role->delete_rules = $request->input('delete_rules') == 'on';
            $role->view_rules_changelog = $request->input('view_rules_changelog') == 'on';

            $role->manage_roles = $request->input('manage_roles') == 'on';
            $role->view_roles = $request->input('view_roles') == 'on';
            $role->add_roles = $request->input('add_roles') == 'on';
            $role->edit_roles = $request->input('edit_roles') == 'on';
            $role->edit_roles_permissions = $request->input('edit_roles_permissions') == 'on';
            $role->delete_roles = $request->input('delete_roles') == 'on';

            $role->manage_kb = $request->input('manage_kb') == 'on';
            $role->view_private = $request->input('view_private') == 'on';
            $role->view_hidden = $request->input('view_hidden') == 'on';
            $role->add_article = $request->input('add_article') == 'on';
            $role->edit_own_article = $request->input('edit_own_article') == 'on';
            $role->edit_all_articles = $request->input('edit_all_articles') == 'on';
            $role->delete_own_article = $request->input('delete_own_article') == 'on';
            $role->delete_all_articles = $request->input('delete_all_articles') == 'on';

            $role->manage_users = $request->input('manage_users') == 'on';
            $role->view_users = $request->input('view_users') == 'on';
            $role->set_user_as_member = $request->input('set_user_as_member') == 'on';

            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Права роли успешно отредактирована!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return redirect()->back();
    }

    public function edit(Request $request, $id){
        $role = Role::findOrFail($id);
        if($request->post() && $id !== false){
            $this->authorize('update', Role::class);
            $this->validate($request, [
                'title' => 'required|string',
                'group' => 'required|string',
                'min_scores' => 'nullable|numeric',
                'max_scores' => 'nullable|numeric',
            ]);
            $role->fill($request->post());
            $role->visible = $request->input('visible') == 'on';
            return $role->save() ?
                redirect()->route('evoque.admin.roles')->with(['success' => 'Роль успешно отредактирована!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        return view('evoque.roles.edit', [
            'role' => $role,
            'roles_list' => Role::where('visible', '1')->get()
        ]);
    }

    public function add(Request $request){
        $this->authorize('create', Role::class);
        $role = new Role;
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
                redirect()->route('evoque.admin.roles', $role->id)->with(['success' => 'Роль успешно добавлена!']) :
                redirect()->back()->withErrors(['Возникла ошибка =(']);
        }
        $role->visible = true;
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
