<?php

namespace App\Policies;

use App\Kb;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KbPolicy extends Policy{

    use HandlesAuthorization;

    public function viewAny(User $user){
        return $this->checkPermission($user, 'manage_kb', 'view_hidden');
    }

    public function viewPrivate(User $user){
        return $this->checkPermission($user, 'manage_kb', 'view_private');
    }

    public function create(User $user){
        return $this->checkPermission($user, 'manage_kb', 'add_article');
    }

    public function update(User $user, Kb $kb){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->edit_all_articles ||
                    ($kb->author == $user->id && $role->edit_own_article)) return true;
            }
        }
        return false;
    }

    public function delete(User $user, Kb $kb){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_kb || $role->delete_all_articles ||
                    ($kb->author == $user->id && $role->delete_own_article)) return true;
            }
        }
        return false;
    }

}
