<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends Policy{

    use HandlesAuthorization;

    public function view(User $user){
        return $this->checkPermission($user, 'manage_users', 'view_users');
    }

    public function setAsMember(User $user, User $model){
        if($user->member && !$model->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_users', 'set_user_as_member'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_users || $role->set_user_as_member) return true;
            }
        }
        return false;
    }

}
