<?php

namespace App\Policies;

use App\Convoy;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConvoyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->view_all_convoys) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->add_convoys) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->edit_convoys) return true;
            }
        }
        return false;
    }

    public function updateOne(User $user, Convoy $convoy){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->edit_convoys || ($convoy->booked_by_id == $user->member->id && !$convoy->visible)) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->delete_convoys) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function book(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->book_convoys) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function quickBook(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_convoys || $role->quick_book_convoys) return true;
            }
        }
        return false;
    }

}
