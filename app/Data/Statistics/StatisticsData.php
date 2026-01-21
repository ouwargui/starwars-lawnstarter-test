<?php

namespace App\Data\Statistics;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * Contains all computed metrics from request logs.
 */
final class StatisticsData extends Data
{
    public function __construct(
        /** @var Collection<int, QueryStatData> */
        public readonly Collection $top_queries,

        public readonly float $average_duration_ms,

        public readonly float $p95_duration_ms,

        public readonly ?HourlyStatData $peak_hour,

        /** @var Collection<int, ResourceStatData> */
        public readonly Collection $top_movies,

        /** @var Collection<int, ResourceStatData> */
        public readonly Collection $top_characters,

        /** @var Collection<int, RefererStatData> */
        public readonly Collection $top_referers,

        /** @var Collection<int, EndpointErrorRateData> */
        public readonly Collection $error_rates,

        public readonly CacheStatsData $swapi_cache_stats,

        public readonly int $requests_last_24h,

        /** @var Collection<int, DailyRequestData> */
        public readonly Collection $daily_breakdown,

        public readonly ?CarbonImmutable $computed_at,
    ) {}

    /**
     * Create a default statistics object for when no data is available.
     */
    public static function default(): self
    {
        return new self(
            top_queries: new Collection,
            average_duration_ms: 0.0,
            p95_duration_ms: 0.0,
            peak_hour: null,
            top_movies: new Collection,
            top_characters: new Collection,
            top_referers: new Collection,
            error_rates: new Collection,
            swapi_cache_stats: new CacheStatsData(0, 0, 0, 0.0),
            requests_last_24h: 0,
            daily_breakdown: new Collection,
            computed_at: null,
        );
    }
}
