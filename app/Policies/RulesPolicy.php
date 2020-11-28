<?php

namespace App\Policies;

use App\Rules;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rules || $role->add_rules) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rules || $role->edit_rules) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rules || $role->delete_rules) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewChangelog(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rules || $role->view_rules_changelog) return true;
            }
        }
        return false;
    }

}
