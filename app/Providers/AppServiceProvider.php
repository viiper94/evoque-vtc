<?php

namespace App\Providers;

use App\Application;
use App\Convoy;
use App\Gallery;
use App\Member;
use App\Recruitment;
use App\RpReport;
use App\Tab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('layout.navbar', function($view){
            View::share('controller', strtolower(class_basename(Route::current()?->getController())));

            $convoys_badge = 0;
            $applications_badge = 0;

            $tabs_c = 0;
            $bookings_c = 0;

            if(Auth::user()?->can('accept', Tab::class)){
                $tabs_c = Tab::where('status', 0)->count();
                $convoys_badge += $tabs_c;
            }
            if(Auth::user()?->can('update', Convoy::class)){
                $bookings_c = Convoy::where(['booking' => '1', 'visible' => '0'])->count();
                $convoys_badge += $bookings_c;
            }

            $applications_c = Application::where('status', 0)->count();
            $recruitments_c = Recruitment::where('status', 0)->count();
            if(Auth::user()?->can('accept', Application::class)) $applications_badge += $applications_c;
            if(Auth::user()?->can('accept', Recruitment::class)) $applications_badge += $recruitments_c;
            View::share([
                'convoys_c' => $convoys_badge,
                'tabs_c' => $tabs_c,
                'bookings_c' => $bookings_c,
                'applications_badge' => $applications_badge,
                'reports_c' => RpReport::where('status', 0)->count()
            ]);
        });
    }
}
