<?php

namespace App\Policies;

use App\RpReport;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RpReportPolicy extends Policy{

    use HandlesAuthorization;

    public function resetStats(User $user){
        return $this->checkPermission($user, 'manage_rp', 'reset_stats');
    }

    public function updateRewards(User $user){
        return $this->checkPermission($user, 'manage_rp', 'edit_rp_rewards');
    }

    public function viewAll(User $user){
        return $this->checkPermission($user, 'manage_rp', 'view_all_reports');
    }

    public function create(User $user){
        if($user->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_rp', 'add_reports'))){
                return $result && $user->member->canReportRP();
            }
            foreach($user->member->role as $role){
                if($role->manage_rp || ($role->add_reports && $user->member->canReportRP())) return true;
            }
        }
        return false;
    }

    public function delete(User $user, RpReport $rpReport){
        if($user->member){
            foreach($user->member->role as $role){
                if(is_bool($result = $this->checkMemberPermission($user, 'manage_rp', 'delete_all_reports'))){
                    return $result;
                }
                if(is_bool($result = $this->checkMemberPermission($user, 'manage_rp', 'delete_own_reports'))){
                    return $user->member->id === $rpReport->member_id && $rpReport->status == 0 && $result;
                }
                if($role->manage_rp ||
                    ($user->member->id === $rpReport->member_id && $rpReport->status == 0 && $role->delete_own_reports) ||
                    $role->delete_all_reports) return true;
            }
        }
        return false;
    }

    public function claim(User $user){
        return $this->checkPermission($user, 'manage_rp', 'accept_reports');
    }

    public function accept(User $user, RpReport $rpReport){
        if($user->member && $rpReport->status == 0 && $rpReport->member){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_rp', 'accept_reports'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->accept_reports) return true;
            }
        }
        return false;
    }

    public function decline(User $user, RpReport $rpReport){
        if($user->member && $rpReport->status == 0){
            if(is_bool($result = $this->checkMemberPermission($user, 'manage_rp', 'accept_reports'))){
                return $result;
            }
            foreach($user->member->role as $role){
                if($role->manage_rp || $role->accept_reports) return true;
            }
        }
        return false;
    }

}
