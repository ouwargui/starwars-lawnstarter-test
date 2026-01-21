<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Event dispatched when statistics computation should be triggered.
 *
 * This event is fired by the scheduler on a configurable interval and handled
 * by a queued listener to compute and cache statistics.
 */
final class StatisticsComputationRequested
{
    use Dispatchable;
}
