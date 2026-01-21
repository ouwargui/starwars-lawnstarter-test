<?php

use App\Jobs\PruneRequestLogsJob;
use App\Models\RequestLog;

describe('PruneRequestLogsJob', function () {
    it('deletes records older than retention period', function () {
        // Create old record (outside 30-day retention)
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subDays(31),
        ]);

        // Create recent record (within retention)
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subDays(5),
        ]);

        expect(RequestLog::count())->toBe(2);

        $job = new PruneRequestLogsJob;
        $job->handle();

        expect(RequestLog::count())->toBe(1);
    });

    it('keeps records within retention period', function () {
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subDays(29),
        ]);

        $job = new PruneRequestLogsJob;
        $job->handle();

        expect(RequestLog::count())->toBe(1);
    });

    it('handles empty database gracefully', function () {
        expect(RequestLog::count())->toBe(0);

        $job = new PruneRequestLogsJob;
        $job->handle();

        expect(RequestLog::count())->toBe(0);
    });

    it('deletes multiple old records', function () {
        // Create multiple old records
        for ($i = 0; $i < 5; $i++) {
            RequestLog::create([
                'endpoint' => 'search',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now()->subDays(35 + $i),
            ]);
        }

        expect(RequestLog::count())->toBe(5);

        $job = new PruneRequestLogsJob;
        $job->handle();

        expect(RequestLog::count())->toBe(0);
    });

    it('deletes records at exactly 30 days', function () {
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subDays(30)->subSecond(),
        ]);

        $job = new PruneRequestLogsJob;
        $job->handle();

        expect(RequestLog::count())->toBe(0);
    });
});
