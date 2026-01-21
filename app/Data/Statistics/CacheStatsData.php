<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for SWAPI API cache hit/miss statistics.
 *
 * Tracks how many SWAPI API calls were served from cache vs fetched fresh.
 */
final class CacheStatsData extends Data
{
    public function __construct(
        public readonly int $hits,
        public readonly int $misses,
        public readonly int $total,
        public readonly float $hit_rate,
    ) {}
}
