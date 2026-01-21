<?php

namespace App\Jobs;

use App\Services\StatisticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Job that computes and caches statistics.
 *
 * This is a thin wrapper that delegates to StatisticsService.
 * Runs on the queue on a configurable interval via the scheduler.
 */
final class ComputeStatisticsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Cache key for storing computed statistics.
     */
    public const CACHE_KEY = 'statistics';

    public function handle(StatisticsService $statisticsService): void
    {
        Log::info('Computing statistics...');

        $statistics = $statisticsService->compute();

        Cache::forever(self::CACHE_KEY, $statistics);

        Log::info('Statistics computed and cached successfully.', [
            'computed_at' => $statistics->computed_at?->toIso8601String(),
            'requests_last_24h' => $statistics->requests_last_24h,
        ]);
    }
}
