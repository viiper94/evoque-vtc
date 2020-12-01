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
                if($role->manage_kb || $role->view_invisible_articles) return true;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function view(User $user, Kb $kb)
    {
        //
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
                if($role->manage_kb || $role->add_kb) return true;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function update(User $user, Kb $kb)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function delete(User $user, Kb $kb)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function restore(User $user, Kb $kb)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Kb  $kb
     * @return mixed
     */
    public function forceDelete(User $user, Kb $kb)
    {
        //
    }
}
