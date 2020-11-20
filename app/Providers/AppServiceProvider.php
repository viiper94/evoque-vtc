<?php

namespace App\Providers;

use App\Application;
use App\Convoy;
use App\Recruitment;
use App\RpReport;
use App\Tab;
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

        View::share('applications_c', Application::where('status', 0)->count());
        View::share('recruitments_c', Recruitment::where('status', 0)->count());
        View::share('tabs_c', Tab::where('status', 0)->count());
        View::share('bookings_c', Convoy::where(['booking' => '1', 'visible' => '0'])->count());
        View::share('reports_c', RpReport::where('status', '0')->count());

    }
}
