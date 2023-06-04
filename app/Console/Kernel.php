<?php

namespace App\Console;

use App\Convoy;
use App\Member;
use App\RpReport;
use App\TestResult;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){

        $schedule->call(function(){
            Member::checkBans();
        })->everySixHours();

        $schedule->call(function(){
            User::deleteOldUsers();
            TestResult::deleteOldResults();
        })->daily();

        $schedule->call(function(){
            Convoy::compressOldImages();
        })->dailyAt('4:27');

        $schedule->call(function(){
            RpReport::compressOldImages();
        })->dailyAt('3:27');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
