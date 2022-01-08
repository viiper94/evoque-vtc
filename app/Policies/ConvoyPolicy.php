<?php

namespace App\Policies;

use App\Convoy;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConvoyPolicy extends Policy{

    use HandlesAuthorization;

    public function viewAny(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'view_all_convoys');
    }

    public function create(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'add_convoys');
    }

    public function update(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'edit_convoys');
    }

    public function updateOne(User $user, Convoy $convoy){
        if($user->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_convoys', 'edit_convoys'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->edit_convoys ||
                    ($convoy->booked_by_id == $user->member->id && !$convoy->visible) ||
                    ($convoy->booked_by_id == $user->member->id && $convoy->visible && Carbon::now()->diffInMinutes($convoy->created_at) <= 15)
                ) return true;
            }
        }
        return false;
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'delete_convoys');
    }

    public function book(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'book_convoys');
    }

    public function quickBook(User $user){
        return $this->checkPermission($user, 'manage_convoys', 'quick_book_convoys');
    }

}
