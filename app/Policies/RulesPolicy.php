<?php

namespace App\Policies;

use App\Rules;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulesPolicy extends Policy{

    use HandlesAuthorization;

    public function create(User $user){
        return $this->checkPermission($user, 'manage_rules', 'add_rules');
    }

    public function update(User $user){
        return $this->checkPermission($user, 'manage_rules', 'edit_rules');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_rules', 'delete_rules');
    }

    public function viewChangelog(User $user){
        return $this->checkPermission($user, 'manage_rules', 'view_rules_changelog');
    }

}
