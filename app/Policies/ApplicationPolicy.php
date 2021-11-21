<?php

namespace App\Policies;

use App\Application;
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
            foreach($user->member->role as $role){
                $hasUnapprovedApplication = Application::where(['member_id' => $user->member->id, 'category' => 1, 'status' => 0])->count() > 0;
                $hasFutureVacation = isset($user->member->on_vacation_till['to']) && Carbon::parse($user->member->on_vacation_till['to'])->isFuture();
                if($role->manage_applications ||
                    ($role->make_applications && $user->member->vacations < 2
                        && !$hasFutureVacation && !$hasUnapprovedApplication)) return true;
            }
        }
        return false;
    }

    public function update(User $user, Application $application){
        if($user->member?->id === $application->member_id && $application->status === 0){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->edit_applications) return true;
            }
        }
        return false;
    }

    public function delete(User $user, Application $application){
        if($user->member && $application->status === 0){
            foreach($user->member->role as $role){
                if($role->manage_applications ||
                    ($role->edit_applications && $user->member->id === $application->member_id && Carbon::now()->lt($application->created_at->addMinutes(15)))
                    || $role->delete_applications) return true;
            }
        }
        return false;
    }

    public function claim(User $user, Application $application){
        if($user->member && $application->status !== 1 && $application->status !== 2){
            foreach($user->member->role as $role){
                if($role->manage_applications || $role->claim_applications) return true;
            }
        }
        return false;
    }

    public function accept(User $user){
        return $this->checkPermission($user, 'manage_applications', 'claim_applications');
    }

}
