<?php

namespace App\Policies;

use App\TestQuestion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestQuestionPolicy extends Policy{

    use HandlesAuthorization;

    public function create(User $user){
        return $this->checkPermission($user, 'manage_test', 'add_questions');
    }

    public function update(User $user){
        return $this->checkPermission($user, 'manage_test', 'edit_questions');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_test', 'delete_questions');
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
