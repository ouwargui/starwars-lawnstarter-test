<?php

use App\Data\Statistics\StatisticsData;
use App\Models\RequestLog;
use App\Services\StatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('compute', function () {
    it('returns StatisticsData instance', function () {
        $service = new StatisticsService;

        $result = $service->compute();

        expect($result)->toBeInstanceOf(StatisticsData::class);
    });

    it('returns empty statistics when no data', function () {
        $service = new StatisticsService;

        $result = $service->compute();

        expect($result->top_queries)->toBe([]);
        expect($result->average_duration_ms)->toBe(0.0);
        expect($result->p95_duration_ms)->toBe(0.0);
        expect($result->peak_hour)->toBeNull();
        expect($result->top_movies)->toBe([]);
        expect($result->top_characters)->toBe([]);
        expect($result->requests_last_24h)->toBe(0);
    });

    it('computes average duration correctly', function () {
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

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 200,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->average_duration_ms)->toBe(150.0);
    });

    it('computes top queries correctly', function () {
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'Luke',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'Luke',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'Vader',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->top_queries)->toHaveCount(2);
        expect($result->top_queries[0]->query)->toBe('Luke');
        expect($result->top_queries[0]->count)->toBe(2);
        expect($result->top_queries[0]->percentage)->toBe(66.67);
    });

    it('computes cache stats correctly', function () {
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 3,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 2,
            'swapi_cache_misses' => 2,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->swapi_cache_stats->hits)->toBe(5);
        expect($result->swapi_cache_stats->misses)->toBe(3);
        expect($result->swapi_cache_stats->total)->toBe(8);
        expect($result->swapi_cache_stats->hit_rate)->toBe(62.5);
    });

    it('computes requests last 24h correctly', function () {
        // Request within 24h
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subHours(1),
        ]);

        // Request outside 24h
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subHours(25),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->requests_last_24h)->toBe(1);
    });

    it('computes error rates correctly', function () {
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

        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 500,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => true,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->error_rates)->toHaveCount(1);
        expect($result->error_rates[0]->endpoint)->toBe('search');
        expect($result->error_rates[0]->total_requests)->toBe(2);
        expect($result->error_rates[0]->error_count)->toBe(1);
        expect($result->error_rates[0]->error_rate)->toBe(50.0);
    });

    it('computes top movies correctly', function () {
        RequestLog::create([
            'endpoint' => 'movies',
            'method' => 'GET',
            'resource_id' => 1,
            'resource_name' => 'A New Hope',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        RequestLog::create([
            'endpoint' => 'movies',
            'method' => 'GET',
            'resource_id' => 1,
            'resource_name' => 'A New Hope',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 1,
            'swapi_cache_misses' => 0,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->top_movies)->toHaveCount(1);
        expect($result->top_movies[0]->id)->toBe(1);
        expect($result->top_movies[0]->name)->toBe('A New Hope');
        expect($result->top_movies[0]->count)->toBe(2);
    });

    it('computes top characters correctly', function () {
        RequestLog::create([
            'endpoint' => 'people',
            'method' => 'GET',
            'resource_id' => 1,
            'resource_name' => 'Luke Skywalker',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->top_characters)->toHaveCount(1);
        expect($result->top_characters[0]->id)->toBe(1);
        expect($result->top_characters[0]->name)->toBe('Luke Skywalker');
        expect($result->top_characters[0]->count)->toBe(1);
    });

    it('excludes old data outside retention period', function () {
        // Old request (outside 30 days)
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'OldQuery',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now()->subDays(31),
        ]);

        // Recent request
        RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'query' => 'NewQuery',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        $service = new StatisticsService;
        $result = $service->compute();

        expect($result->top_queries)->toHaveCount(1);
        expect($result->top_queries[0]->query)->toBe('NewQuery');
    });
});
