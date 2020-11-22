<?php

namespace App\Policies;

use App\Tab;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TabPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)    {
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tab || $role->view_tab) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tab  $tab
     * @return mixed
     */
    public function update(User $user, Tab $tab){
        if($user->member && $tab->status == 0){
            foreach($user->member->role as $role){
                if($role->edit_tab && $tab->member_id === $user->member->id) return true;
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
                if($role->manage_convoys || $role->manage_tab || $role->book_convoys) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tab  $tab
     * @return mixed
     */
    public function claim(User $user, Tab $tab){
        if($user->member && $tab->status == 0){
            foreach($user->member->role as $role){
                if($role->manage_tab || $role->accept_tab) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tab  $tab
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tab || $role->delete_tab) return true;
            }
        }
        return false;
    }

}
