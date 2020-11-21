<?php

namespace App\Policies;

use App\Recruitment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecruitmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view recruitment.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->view_recruitments) return true;
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
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recruitment  $recruitment
     * @return mixed
     */
    public function update(User $user, Recruitment $recruitment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recruitment  $recruitment
     * @return mixed
     */
    public function delete(User $user, Recruitment $recruitment)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recruitment  $recruitment
     * @return mixed
     */
    public function restore(User $user, Recruitment $recruitment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recruitment  $recruitment
     * @return mixed
     */
    public function forceDelete(User $user, Recruitment $recruitment)
    {
        //
    }
}
