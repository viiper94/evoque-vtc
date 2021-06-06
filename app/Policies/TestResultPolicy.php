<?php

namespace App\Policies;

use App\TestResult;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestResultPolicy
{
    use HandlesAuthorization;

    public function do(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->do_test) return true;
            }
        }
        return false;
    }

    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->view_results) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->delete_results) return true;
            }
        }
        return false;
    }

}
