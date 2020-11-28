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
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->delete_recruitments) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept or decline specific model.
     *
     * @param  \App\User  $user
     * @param  \App\Recruitment  $recruitment
     * @return mixed
     */
    public function claim(User $user, Recruitment $recruitment){
        if($user->member && $recruitment->status == 0){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_recruitments) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept or decline recruitment at all.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function accept(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_recruitments) return true;
            }
        }
        return false;
    }

}
