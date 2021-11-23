<?php

namespace App\Policies;

use App\TestResult;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestResultPolicy extends Policy{

    use HandlesAuthorization;

    public function do(User $user){
        return $this->checkPermission($user, 'manage_test', 'do_test');
    }

    public function view(User $user){
        return $this->checkPermission($user, 'manage_test', 'view_results');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_test', 'delete_results');
    }

}
