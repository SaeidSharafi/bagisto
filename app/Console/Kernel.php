<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Kuro\LaravelSms\Model\SmsLog;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands
        = [
            //
        ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('booking:cron')->dailyAt('3:00');
        $schedule->command('invoice:cron')->dailyAt('3:00');
        $schedule->command('product:price-rule:index')->dailyAt('3:00');
        $schedule->command('moodle:users')->everyMinute()->withoutOverlapping();
        $schedule->command('moodle:enrol')->everyMinute()->withoutOverlapping();
        $schedule->command('order:cancel-pending')->everyMinute()->withoutOverlapping();
        $schedule->command('user:generate-token')->everyMinute()->withoutOverlapping();
        $schedule->command('order:fix-customer')->everyMinute()->withoutOverlapping();
        $schedule->command('model:prune', [
            '--model' => [SmsLog::class]
        ])->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/../../packages/Webkul/Core/src/Console/Commands');

        require base_path('routes/console.php');
    }
}