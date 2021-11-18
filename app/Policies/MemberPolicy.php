<?php

namespace App\Policies;

use App\Member;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function resetActivity(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->reset_members_activity) return true;
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
    public function setActivity(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->set_members_activity) return true;
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
                if($role->manage_members || $role->edit_members) return true;
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
    public function updateRpStats(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->edit_members_rp_stats) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function updateRoles(User $user, Member $member){
        if($member->topRole() >= $user->member?->topRole() || $user->member?->topRole() === 1){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->edit_members) return true;
            }
        }
        return false;
    }

    public function updatePermissions(User $user, Member $member){
        if($user->member || $user->member?->topRole() === 1){
            foreach($user->member->role as $role){
                if($role->manage_roles || $role->edit_roles_permissions ||
                    ($role->manage_members && $member->topRole() >= $user->member?->topRole())) return true;
            }
        }
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function fire(User $user, Member $member){
        if($member->topRole() >= $user->member?->topRole()){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->fire_members) return true;
            }
        }
        return false;
    }

    public function seeBans(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->see_bans) return true;
            }
        }
        return false;
    }

    public function restore(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->restore_members) return true;
            }
        }
        return true;
    }

}
