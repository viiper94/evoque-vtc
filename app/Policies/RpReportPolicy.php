<?php

namespace App\Policies;

use App\RpReport;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RpReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function resetStats(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->reset_stats) return true;
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
    public function viewAll(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->view_all_reports) return true;
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
                if($role->manage_rp || $role->add_reports) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\RpReport  $rpReport
     * @return mixed
     */
    public function delete(User $user, RpReport $rpReport){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rp ||
                    ($user->member->id === $rpReport->member_id && $rpReport->status == 0 && $role->delete_own_reports) ||
                    $role->delete_all_reports) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept any reports at all.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function claim(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->accept_reports) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can accept the model.
     *
     * @param  \App\User  $user
     * @param  \App\RpReport  $rpReport
     * @return mixed
     */
    public function accept(User $user, RpReport $rpReport){
        if($user->member && $rpReport->status == 0){
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->accept_reports) return true;
            }
        }
        return false;
    }

}
