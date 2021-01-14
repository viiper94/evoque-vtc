<?php

namespace App\Policies;

use App\Gallery;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_gallery || $role->upload_screenshots) return true;
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
    public function forceCreate(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_gallery || ($role->upload_screenshots && $role->upload_without_moderation)) return true;
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
    public function toggle(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_gallery || $role->toggle_visibility) return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gallery  $gallery
     * @return mixed
     */
    public function delete(User $user){
        if($user->member){
            foreach($user->member->role as $role){
                if($role->manage_gallery || $role->delete_screenshots) return true;
            }
        }
        return false;
    }

}
