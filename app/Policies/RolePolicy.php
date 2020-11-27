<?php

namespace App\Policies;

use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->view_roles) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->add_roles) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function update(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->edit_roles) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function updatePermissions(User $user, Role $role){
        if($user->member && $user->member->topRole() <= $role->id){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->edit_roles_permissions) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->delete_roles) return true;
            }
        }
        return false;
    }

}
