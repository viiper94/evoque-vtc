<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TuningPolicy extends Policy{

    use HandlesAuthorization;

    public function view(User $user){
        return $this->checkPermission($user, 'manage_tunings', 'view_tunings');
    }

    public function add(User $user){
        return $this->checkPermission($user, 'manage_tunings', 'add_tunings');
    }

    public function edit(User $user){
        return $this->checkPermission($user, 'manage_tunings', 'edit_tunings');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_tunings', 'delete_tunings');
    }

}
