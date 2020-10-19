<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->admin) return true;
                }
            }
            return false;
        });

        Gate::define('manage_members', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->manage_members || $role->admin) return true;
                }
            }
            return false;
        });

        Gate::define('manage_convoys', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->manage_convoys || $role->admin) return true;
                }
            }
            return false;
        });

        Gate::define('lead_convoys', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->lead_convoys || $role->admin) return true;
                }
            }
            return true;
        });

        Gate::define('manage_table', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->manage_members || $role->manage_table || $role->admin) return true;
                }
            }
            return false;
        });

        Gate::define('manage_rp', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->manage_rp || $role->admin) return true;
                }
            }
            return false;
        });

        Gate::define('do_rp', function($user){
            if($user->member){
                foreach($user->member->role as $role){
                    if($role->do_rp && !$user->member->onVacation() || $role->admin) return true;
                }
            }
            return false;
        });

    }
}
