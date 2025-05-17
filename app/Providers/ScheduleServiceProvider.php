<?php

namespace App\Providers;

use App\Models\PsychotestSession;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            // Update status sesi psikotest
            $schedule->command('psychotest:update-sessions')
                ->everyMinute()
                ->appendOutputTo(storage_path('logs/laravel.log'));
        });
    }
}
