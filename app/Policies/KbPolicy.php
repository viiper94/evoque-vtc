<?php

namespace App\Policies;

use App\Kb;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KbPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->view_hidden) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewPrivate(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->view_private) return true;
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
                if($role->manage_kb || $role->add_article) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function update(User $user, Kb $kb){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->edit_all_articles ||
                    ($kb->author == $user->id && $role->edit_own_article)) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function delete(User $user, Kb $kb){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->delete_all_articles ||
                    ($kb->author == $user->id && $role->delete_own_article)) return true;
            }
        }
        return false;
    }

}
