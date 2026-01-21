<?php

use App\Data\StatisticsData;
use App\Jobs\ComputeStatisticsJob;
use App\Models\RequestLog;
use Illuminate\Support\Facades\Cache;

describe('ComputeStatisticsJob', function () {
    it('computes and caches statistics', function () {
        Cache::forget(ComputeStatisticsJob::CACHE_KEY);

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'Luke',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 1,
            'swapi_cache_misses' => 2,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $job = new ComputeStatisticsJob;
        $job->handle(app(\App\Services\StatisticsService::class));

        $cached = Cache::get(ComputeStatisticsJob::CACHE_KEY);

        expect($cached)->toBeInstanceOf(StatisticsData::class);
        expect($cached->requests_last_24h)->toBe(1);
    });

    it('stores computed_at timestamp', function () {
        Cache::forget(ComputeStatisticsJob::CACHE_KEY);

        $job = new ComputeStatisticsJob;
        $job->handle(app(\App\Services\StatisticsService::class));

        $cached = Cache::get(ComputeStatisticsJob::CACHE_KEY);

        expect($cached->computed_at)->not->toBeNull();
    });

    it('updates existing cached statistics', function () {
        // First computation
        $job = new ComputeStatisticsJob;
        $job->handle(app(\App\Services\StatisticsService::class));

        $firstComputed = Cache::get(ComputeStatisticsJob::CACHE_KEY)->computed_at;

        // Add new data
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        // Wait a moment to ensure different timestamp
        usleep(10000);

        // Second computation
        $job->handle(app(\App\Services\StatisticsService::class));

        $secondComputed = Cache::get(ComputeStatisticsJob::CACHE_KEY)->computed_at;

        expect($secondComputed->isAfter($firstComputed))->toBeTrue();
    });

    it('computes correct cache stats', function () {
        Cache::forget(ComputeStatisticsJob::CACHE_KEY);

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 5,
            'swapi_cache_misses' => 2,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $job = new ComputeStatisticsJob;
        $job->handle(app(\App\Services\StatisticsService::class));

        $cached = Cache::get(ComputeStatisticsJob::CACHE_KEY);

        expect($cached->cache_stats->hits)->toBe(5);
        expect($cached->cache_stats->misses)->toBe(2);
    });
});
