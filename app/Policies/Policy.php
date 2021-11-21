<?php

namespace App\Policies;

class Policy{

    public function checkPermission($user, $parent, $permission){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->$parent || $role->$permission) return true;
            }
        }
        return false;
    }

}
