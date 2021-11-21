<?php

namespace App\Policies;

use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy extends Policy{

    use HandlesAuthorization;

    public function view(User $user){
        return $this->checkPermission($user, 'manage_roles', 'view_roles');
    }

    public function create(User $user){
        return $this->checkPermission($user, 'manage_roles', 'add_roles');
    }

    public function update(User $user){
        return $this->checkPermission($user, 'manage_roles', 'edit_roles');
    }

    public function updatePermissions(User $user, Role $role){
        if($user->member?->topRole() < $role->id || $user->member?->topRole() === 0){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->edit_roles_permissions) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_roles', 'delete_roles');
    }

}
