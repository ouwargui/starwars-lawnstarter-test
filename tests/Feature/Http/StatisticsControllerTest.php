<?php

use App\Data\StatisticsData;
use App\Jobs\ComputeStatisticsJob;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

describe('statistics endpoint', function () {
    it('returns default statistics when no data is cached', function () {
        Cache::forget(ComputeStatisticsJob::CACHE_KEY);

        /** @var TestCase $this */
        $this->getJson('/api/statistics')
            ->assertOk()
            ->assertJson([
                'top_queries' => [],
                'average_duration_ms' => 0.0,
                'p95_duration_ms' => 0.0,
                'peak_hour' => null,
                'top_movies' => [],
                'top_characters' => [],
                'requests_last_24h' => 0,
            ]);
    });

    it('returns cached statistics when available', function () {
        $statistics = StatisticsData::default();

        Cache::forever(ComputeStatisticsJob::CACHE_KEY, $statistics);

        /** @var TestCase $this */
        $this->getJson('/api/statistics')
            ->assertOk()
            ->assertJson([
                'top_queries' => [],
                'average_duration_ms' => 0.0,
                'requests_last_24h' => 0,
            ]);
    });

    it('returns JSON response', function () {
        Cache::forget(ComputeStatisticsJob::CACHE_KEY);

        /** @var TestCase $this */
        $this->getJson('/api/statistics')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json');
    });
});
