<?php

namespace App\Policies;

use App\TestQuestion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

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
            return Auth::user()->can('create', TestQuestion::class)
                || Auth::user()->can('update', TestQuestion::class)
                || Auth::user()->can('delete', TestQuestion::class);
        }
        return false;
    }

}
