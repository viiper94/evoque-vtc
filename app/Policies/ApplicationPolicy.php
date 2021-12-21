<?php

namespace App\Policies;

use App\Application;
use App\Comment;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy extends Policy{

    use HandlesAuthorization;

    public function view(User $user, Application $app){
        if($user->member?->id === $app->member_id) return true;
        return $this->checkPermission($user, 'manage_applications', 'view_applications');
    }

    public function viewAll(User $user){
        return $this->checkPermission($user, 'manage_applications', 'view_applications');
    }

    public function create(User $user){
        return $this->checkPermission($user, 'manage_applications', 'make_applications');
    }

    public function createVacation(User $user){
        if($user->member){
            $hasUnapprovedApplication = Application::where(['member_id' => $user->member->id, 'category' => 1, 'status' => 0])->count() > 0;
            $hasFutureVacation = isset($user->member->on_vacation_till['to']) && Carbon::parse($user->member->on_vacation_till['to'])->isFuture();
            $noMoreThan2PastVacations = $user->member->vacations < 2;

            $condition = !$hasUnapprovedApplication && !$hasFutureVacation && $noMoreThan2PastVacations;
            $member_parent = $user->member->permissions['manage_applications'] ?? null;
            $member_permission = $user->member->permissions['make_applications'] ?? null;
            $role_parent = false;
            $role_permission = false;
            foreach($user->member->role as $role){
                $role_parent = $role->manage_applications || $role_parent;
                $role_permission = $role->make_applications || $role_permission;
            }
            if($member_parent === 'on') return true;
            if($member_permission === 'on' && $condition) return true;
            if($member_permission === 'off') return false;
            if($role_parent || $role_permission && $condition) return true;
        }
        return false;
    }

    public function update(User $user, Application $application){
        if($user->member?->id === $application->member_id && $application->status === 0){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_applications', 'edit_applications'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->edit_applications) return true;
            }
        }
        return false;
    }

    public function delete(User $user, Application $application){
        if($user->member && $application->status === 0){
            $condition = $user->member->id === $application->member_id && Carbon::now()->lt($application->created_at->addMinutes(15));
            $member_parent = $user->member->permissions['manage_applications'] ?? null;
            $member_permission = $user->member->permissions['delete_applications'] ?? null;
            $role_parent = false;
            $role_permission = false;
            foreach($user->member->role as $role){
                $role_parent = $role->manage_applications || $role_parent;
                $role_permission = $role->delete_applications || $role_permission;
            }
            if($member_parent === 'on') return true;
            if($member_permission === 'on' && $condition) return true;
            if($member_permission === 'off') return false;
            if($role_parent || $role_permission && $condition) return true;
        }
        return false;
    }

    public function claim(User $user, Application $application){
        if($user->member && !$application->isClosed()){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_applications', 'claim_applications'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_applications) return true;
            }
        }
        return false;
    }

    public function accept(User $user){
        return $this->checkPermission($user, 'manage_applications', 'claim_applications');
    }

    public function addComment(User $user, Application $application){
        if($user->member){
            if(!$application->isClosed() && $user->member->id === $application->member_id) return true;
        }
        return false;
    }

    public function deleteComment(User $user, Application $application, Comment $comment){
        if($user->member){
            if(!$application->isClosed() && $user->id === $comment->author_id) return true;
        }
        return false;
    }

}
