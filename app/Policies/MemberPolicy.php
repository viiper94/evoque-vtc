<?php

namespace App\Policies;

use App\Member;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy extends Policy{

    use HandlesAuthorization;

    public function resetActivity(User $user){
        return $this->checkPermission($user, 'manage_members', 'reset_members_activity');
    }

    public function setActivity(User $user){
        return $this->checkPermission($user, 'manage_members', 'set_members_activity');
    }

    public function update(User $user){
        return $this->checkPermission($user, 'manage_members', 'edit_members');
    }

    public function updateRpStats(User $user){
        return $this->checkPermission($user, 'manage_members', 'edit_members_rp_stats');
    }

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

    public function fire(User $user, Member $member){
        if($member->topRole() >= $user->member?->topRole()){
            foreach($user->member->role as $role){
                if($role->manage_members || $role->fire_members) return true;
            }
        }
        return false;
    }

    public function seeBans(User $user){
        return $this->checkPermission($user, 'manage_members', 'see_bans');
    }

    public function restore(User $user){
        return $this->checkPermission($user, 'manage_members', 'restore_members');
    }

}
