<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrucksTuningPolicy{
    use HandlesAuthorization;

    public function view(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tunings || $role->view_tunings) return true;
            }
        }
        return false;
    }

    public function add(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tunings || $role->add_tunings) return true;
            }
        }
        return false;
    }

    public function edit(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tunings || $role->edit_tunings) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_tunings || $role->delete_tunings) return true;
            }
        }
        return false;
    }

}
