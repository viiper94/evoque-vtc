<?php

namespace App\Policies;

use App\TestQuestion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestQuestionPolicy
{
    use HandlesAuthorization;

    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->add_questions) return true;
            }
        }
        return false;
    }

    public function update(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->edit_questions) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->delete_questions) return true;
            }
        }
        return false;
    }

    public function accessToEditPage(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_test || $role->delete_questions || $role->add_questions || $role->edit_questions) return true;
            }
        }
        return false;
    }

}
