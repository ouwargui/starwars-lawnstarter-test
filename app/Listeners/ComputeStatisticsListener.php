<?php

namespace App\Listeners;

use App\Events\StatisticsComputationRequested;
use App\Jobs\ComputeStatisticsJob;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener that dispatches the statistics computation job.
 *
 * Implements ShouldQueue to ensure the computation runs
 * asynchronously on the queue, not blocking the scheduler.
 */
final class ComputeStatisticsListener implements ShouldQueue
{
    /**
     * The name of the queue the job should be sent to.
     */
    public string $queue = 'default';

    public function handle(StatisticsComputationRequested $event): void
    {
        ComputeStatisticsJob::dispatch();
    }
}
