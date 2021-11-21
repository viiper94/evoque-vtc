<?php

namespace App\Policies;

class Policy{

    protected function checkPermission($user, $parent, $permission) :bool{
        if($user->member){

            if(is_bool($result = $this->checkMemberPermission($user, $parent, $permission))){
                return $result;
            }

            foreach($user->member->role as $role){
                if($role->$parent || $role->$permission) return true;
            }
        }
        return false;
    }

    protected function checkMemberPermission($user, $parent, $permission){
            $member_parent = $user->member->permissions[$parent] ?? null;
            $member_permission = $user->member->permissions[$permission] ?? null;
            if($member_parent === 'on' || $member_permission === 'on') return true;
            if($member_permission === 'off') return false;
        return null;
    }

}
