<?php namespace Wawjob\Console;
use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Wawjob\Notification;
use Wawjob\UserNotification;

use Wawjob\Cronjob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Wawjob\Console\Commands\Inspire',
        'Wawjob\Console\Commands\IndexDocumentation',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('docs:index')->hourly();
        //Call the Notification Sending Cron
        $schedule->call(function () {
            Notification::sendByCron();
        })->weekly()->wednesdays()->at(date('H:i'));
        
        //Call the Notification Delete Cron
        $schedule->call(function () {
            UserNotification::deleteByCron();
        })->weekly()->wednesdays()->at(date('H:i'));

        // re-generate hourly_log_maps every hour
        $schedule->call(function () {
            // check the records of `hourly_logs` which have created_at, updated_at, deleted_at >= this_job_last_run_at
            Cronjob::crHourlyLogMap();
        })->hourly();

        // re-generate hourly_log_maps every hour
        $schedule->call(function () {
            Cronjob::crUserProfile();
        })->weekly()->tuesdays()->at('00:00');

        // check weekly timelog    
        $schedule->call(function () {
            // we allow hourly contractors to review their hourly log of last week (the week which has just ended) for 12 hours from UTC midnight of new Monday,
            // and we should consider up to 2 hours of cache time,
            // so we need to start this cron job 14 hours after midnight
            Transaction::payLastHourlyContracts();
        })->weekly()->mondays()->at('14:00');

        // process pending hourly transactions
        $schedule->call(function () {
            Transactions::processPending(['type' => 'hourly']);
        })->weekly()->wednesdays()->at('00:30');

        // process pending fixed-price and bonus transactions
        $schedule->call(function () {
            Transactions::processPending(['type' => 'fixed']);
        })->daily()->at('04:00');
    }
}
