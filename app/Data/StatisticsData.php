<?php

namespace App\Data;

use App\Data\Statistics\CacheStatsData;
use App\Data\Statistics\DailyRequestData;
use App\Data\Statistics\EndpointErrorRateData;
use App\Data\Statistics\HourlyStatData;
use App\Data\Statistics\QueryStatData;
use App\Data\Statistics\RefererStatData;
use App\Data\Statistics\ResourceStatData;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

/**
 * Contains all computed metrics from request logs.
 */
final class StatisticsData extends Data
{
    public function __construct(
        #[DataCollectionOf(QueryStatData::class)]
        public readonly array $top_queries,

        public readonly float $average_duration_ms,

        public readonly float $p95_duration_ms,

        public readonly ?HourlyStatData $peak_hour,

        #[DataCollectionOf(ResourceStatData::class)]
        public readonly array $top_movies,

        #[DataCollectionOf(ResourceStatData::class)]
        public readonly array $top_characters,

        #[DataCollectionOf(RefererStatData::class)]
        public readonly array $top_referers,

        #[DataCollectionOf(EndpointErrorRateData::class)]
        public readonly array $error_rates,

        public readonly CacheStatsData $cache_stats,

        public readonly int $requests_last_24h,

        #[DataCollectionOf(DailyRequestData::class)]
        public readonly array $daily_breakdown,

        public readonly ?CarbonImmutable $computed_at,
    ) {}

    /**
     * Create a default statistics object for when no data is available.
     */
    public static function default(): self
    {
        return new self(
            top_queries: [],
            average_duration_ms: 0.0,
            p95_duration_ms: 0.0,
            peak_hour: null,
            top_movies: [],
            top_characters: [],
            top_referers: [],
            error_rates: [],
            cache_stats: new CacheStatsData(0, 0, 0, 0.0),
            requests_last_24h: 0,
            daily_breakdown: [],
            computed_at: null,
        );
    }
}
