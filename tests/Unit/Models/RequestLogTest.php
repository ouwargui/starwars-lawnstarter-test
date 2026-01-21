<?php

use App\Models\RequestLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('scopes', function () {
    describe('withinDays', function () {
        it('includes records within the specified days', function () {
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

            $results = RequestLog::withinDays(7)->get();

            expect($results)->toHaveCount(1);
        });

        it('excludes records outside the specified days', function () {
            RequestLog::create([
                'endpoint' => 'search',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now()->subDays(10),
            ]);

            $results = RequestLog::withinDays(7)->get();

            expect($results)->toHaveCount(0);
        });
    });

    describe('forEndpoint', function () {
        it('filters by endpoint', function () {
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
                'endpoint' => 'movies',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            $results = RequestLog::forEndpoint('search')->get();

            expect($results)->toHaveCount(1);
            expect($results->first()->endpoint)->toBe('search');
        });
    });

    describe('withErrors', function () {
        it('includes only records with errors', function () {
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

            $results = RequestLog::withErrors()->get();

            expect($results)->toHaveCount(1);
            expect($results->first()->is_error)->toBeTrue();
        });
    });

    describe('withQuery', function () {
        it('includes only records with a search query', function () {
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
                'endpoint' => 'movies',
                'method' => 'GET',
                'query' => null,
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            $results = RequestLog::withQuery()->get();

            expect($results)->toHaveCount(1);
            expect($results->first()->query)->toBe('Luke');
        });
    });

    describe('movies', function () {
        it('filters for movie endpoint', function () {
            RequestLog::create([
                'endpoint' => 'movies',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            RequestLog::create([
                'endpoint' => 'people',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            $results = RequestLog::movies()->get();

            expect($results)->toHaveCount(1);
            expect($results->first()->endpoint)->toBe('movies');
        });
    });

    describe('people', function () {
        it('filters for people endpoint', function () {
            RequestLog::create([
                'endpoint' => 'movies',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            RequestLog::create([
                'endpoint' => 'people',
                'method' => 'GET',
                'duration_ms' => 100,
                'status_code' => 200,
                'swapi_cache_hits' => 0,
                'swapi_cache_misses' => 1,
                'is_error' => false,
                'created_at' => now(),
            ]);

            $results = RequestLog::people()->get();

            expect($results)->toHaveCount(1);
            expect($results->first()->endpoint)->toBe('people');
        });
    });
});

describe('casts', function () {
    it('casts created_at to immutable datetime', function () {
        $log = RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 200,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => false,
            'created_at' => now(),
        ]);

        expect($log->created_at)->toBeInstanceOf(\Carbon\CarbonImmutable::class);
    });

    it('casts is_error to boolean', function () {
        $log = RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => 100,
            'status_code' => 500,
            'swapi_cache_hits' => 0,
            'swapi_cache_misses' => 1,
            'is_error' => true,
            'created_at' => now(),
        ]);

        expect($log->is_error)->toBeTrue();
        expect($log->is_error)->toBeBool();
    });

    it('casts numeric fields to integers', function () {
        $log = RequestLog::create([
            'endpoint' => 'search',
            'method' => 'GET',
            'duration_ms' => '100',
            'status_code' => '200',
            'swapi_cache_hits' => '5',
            'swapi_cache_misses' => '3',
            'is_error' => false,
            'created_at' => now(),
        ]);

        expect($log->duration_ms)->toBeInt();
        expect($log->status_code)->toBeInt();
        expect($log->swapi_cache_hits)->toBeInt();
        expect($log->swapi_cache_misses)->toBeInt();
    });
});
