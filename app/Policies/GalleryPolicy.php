<?php

namespace App\Policies;

use App\Gallery;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy extends Policy{

    use HandlesAuthorization;

    public function create(User $user){
        return $this->checkPermission($user, 'manage_gallery', 'upload_screenshots');
    }

    public function forceCreate(User $user){
        if($user->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_gallery', 'upload_without_moderation'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_gallery || ($role->upload_screenshots && $role->upload_without_moderation)) return true;
            }
        }
        return false;
    }

    public function toggle(User $user){
        return $this->checkPermission($user, 'manage_gallery', 'toggle_visibility');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_gallery', 'delete_screenshots');
    }

}
