<?php

namespace App\Policies;

use App\Tab;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TabPolicy extends Policy{

    use HandlesAuthorization;

    public function viewAny(User $user){
        return $this->checkPermission($user, 'manage_tab', 'view_tab');
    }

    public function update(User $user, Tab $tab){
        if($user->member && $tab->status == 0){
            foreach($user->member->role as $role){
                if($role->edit_tab && $tab->member_id === $user->member->id) return true;
            }
        }
        return false;
    }

    public function create(User $user){
        if($user->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_convoys', 'manage_convoys'))){
                return $result;
            }
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_tab', 'book_convoys'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->manage_tab || $role->book_convoys) return true;
            }
        }
        return false;
    }

    public function accept(User $user){
        return $this->checkPermission($user, 'manage_tab', 'accept_tab');
    }

    public function claim(User $user, Tab $tab){
        if($user->member && $tab->status == 0){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_tab', 'accept_tab'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_tab || $role->accept_tab) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_tab', 'delete_tab');
    }

}
