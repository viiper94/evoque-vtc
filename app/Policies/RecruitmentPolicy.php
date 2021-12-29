<?php

namespace App\Policies;

use App\Comment;
use App\Recruitment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecruitmentPolicy extends Policy{

    use HandlesAuthorization;

    public function view(User $user){
        return $this->checkPermission($user, 'manage_applications', 'view_recruitments');
    }

    public function delete(User $user){
        return $this->checkPermission($user, 'manage_applications', 'delete_recruitments');
    }

    public function claim(User $user, Recruitment $recruitment){
        if($user->member && $recruitment->status != 1 && $recruitment->status != 2){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_applications', 'claim_recruitments'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_recruitments) return true;
            }
        }
        return false;
    }

    public function accept(User $user){
        return $this->checkPermission($user, 'manage_applications', 'claim_recruitments');
    }

    public function addComment(User $user, Recruitment $recruitment){
        if($user->member){
            if(!$recruitment->isClosed() && $user->member->id === $recruitment->member_id) return true;
            if($user->can('claim', $recruitment)) return true;
        }
        return false;
    }

    public function deleteComment(User $user, Recruitment $recruitment, Comment $comment){
        if($user->member){
            if(!$recruitment->isClosed() && $user->id === $comment->author_id) return true;
        }
        return false;
    }

}
