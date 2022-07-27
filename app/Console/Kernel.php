<?php

namespace App\Console;

use App\Models\Service;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('clean:products')->hourly();
//        $schedule->command('clean:product-after-one-day')->hourly();
        $schedule->command('recharge:acb')->everyMinute();

        $this->serviceCheckLiveFbSchedule($schedule);
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

    private function serviceCheckLiveFbSchedule(Schedule $schedule)
    {
        $records = Service::query()
            ->whereIsLocal(true)
            ->whereJsonContains('extras->check_live_facebook', '1');

        $records->chunk(100, function ($data) use ($schedule) {
            foreach ($data as $service) {
                $enable = (bool) $service->extras?->get('check_live_facebook', false);
                $time = $service->extras?->get('check_live_facebook_after', 5);

                if (! $enable) {
                    continue;
                }

                $schedule->command('product:check-live-facebook', ['serviceId' => $service->id])->cron("*/$time * * * *");
            }
        });
    }
}
