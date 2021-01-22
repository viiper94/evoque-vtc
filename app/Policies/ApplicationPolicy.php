<?php

namespace App\Policies;

use App\Application;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view all applications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->view_applications) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create application.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->make_applications) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create vacation application.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createVacation(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || ($role->make_applications && $user->member->vacations < 2)) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update his own applications.
     *
     * @param  \App\User  $user
     * @param  \App\Application  $application
     * @return mixed
     */
    public function update(User $user, Application $application){
        if($user->member && $user->member->id === $application->member_id && $application->status === 0){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->edit_applications) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Application  $application
     * @return mixed
     */
    public function delete(User $user, Application $application){
        if($user->member && $application->status === 0){
            foreach($user->member->role as $role){
                if($role->manage_applications ||
                    ($role->edit_applications && $user->member->id === $application->member_id && Carbon::now()->lt($application->created_at->addMinutes(15)))
                    || $role->delete_applications) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept or decline applications.
     *
     * @param  \App\User  $user
     * @param  \App\Application  $application
     * @return mixed
     */
    public function claim(User $user, Application $application){
        if($user->member && $application->status == 0){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_applications) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept or decline applications.
     *
     * @param  \App\User  $user
     * @param  \App\Application  $application
     * @return mixed
     */
    public function accept(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_applications) return true;
            }
        }
        return false;
    }

}
