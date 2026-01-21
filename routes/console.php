<?php

use App\Events\StatisticsComputationRequested;
use App\Jobs\PruneRequestLogsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(fn () => event(new StatisticsComputationRequested))
    ->everyFiveMinutes()
    ->name('compute-statistics')
    ->withoutOverlapping();

Schedule::job(new PruneRequestLogsJob)
    ->daily()
    ->name('prune-request-logs')
    ->withoutOverlapping();
