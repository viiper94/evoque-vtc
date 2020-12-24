<?php

namespace App\Providers;

use App\Application;
use App\Convoy;
use App\Member;
use App\Recruitment;
use App\RpReport;
use App\Tab;
use Illuminate\Support\Facades\Auth;
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
        Validator::extend('no_vk', function ($attribute, $value, $parameters, $validator) {
            return !stripos($value, 'userapi.com');
        });

        Validator::extend('nullPlate', function ($attribute, $value, $parameters, $validator) {
            $member = Member::where('user_id', Auth::id())->first();
            if($member->isTrainee()) return true;
            else{
                preg_match('%([0-9]{1,3})%', $value, $match);
                return isset($value) && count($match) > 0;
            }
        });

        Validator::extend('uniquePlate', function ($attribute, $value, $parameters, $validator) {
            if (!isset($value)) return true;
            if(strlen($value) === 1) $value = '00'.$value;
            if(strlen($value) === 2) $value = '0'.$value;
            $match = Member::where([
                ['plate', '=', $value],
                ['user_id', '!=', Auth::id()]
            ])->first();
            return $match ? false : true;
        });

        view()->composer('*', function($view){
            $convoys_badge = 0;
            $applications_badge = 0;

            $bookings_c = Convoy::where(['booking' => '1', 'visible' => '0'])->count();
            $tabs_c = Tab::where('status', 0)->count();
            if(Auth::check() && Auth::user()->can('accept', Tab::class)) $convoys_badge += $tabs_c;
            if(Auth::check() && Auth::user()->can('update', Convoy::class)) $convoys_badge += $bookings_c;
            View::share('convoys_c', $convoys_badge);
            View::share('tabs_c', $tabs_c);
            View::share('bookings_c', $bookings_c);

            $applications_c = Application::where('status', 0)->count();
            $recruitments_c = Recruitment::where('status', 0)->count();
            if(Auth::check() && Auth::user()->can('accept', Application::class)) $applications_badge += $applications_c;
            if(Auth::check() && Auth::user()->can('accept', Recruitment::class)) $applications_badge += $recruitments_c;
            View::share('applications_badge', $applications_badge);
            View::share('applications_c', $applications_c);
            View::share('recruitments_c', $recruitments_c);

            View::share('reports_c', RpReport::where('status', '0')->count());
        });
    }
}
