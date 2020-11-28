<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_users || $role->view_users) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function setAsMember(User $user, User $model){
        if($user->member && !$model->member){
            foreach($user->member->role as $role){
                if($role->manage_users || $role->set_user_as_member) return true;
            }
        }
        return false;
    }

}
