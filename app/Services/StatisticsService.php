<?php

namespace App\Services;

use App\Data\Statistics\CacheStatsData;
use App\Data\Statistics\DailyRequestData;
use App\Data\Statistics\EndpointErrorRateData;
use App\Data\Statistics\HourlyStatData;
use App\Data\Statistics\QueryStatData;
use App\Data\Statistics\RefererStatData;
use App\Data\Statistics\ResourceStatData;
use App\Data\StatisticsData;
use App\Models\RequestLog;
use Illuminate\Support\Facades\DB;

/**
 * Service for computing statistics from request logs.
 *
 * All query logic is encapsulated here for testability and reusability.
 */
final class StatisticsService
{
    private function retentionDays(): int
    {
        return config('statistics.retention_days');
    }

    private function topLimit(): int
    {
        return config('statistics.top_limit');
    }

    public function compute(): StatisticsData
    {
        return new StatisticsData(
            top_queries: $this->computeTopQueries(),
            average_duration_ms: $this->computeAverageDuration(),
            p95_duration_ms: $this->computeP95Duration(),
            peak_hour: $this->computePeakHour(),
            top_movies: $this->computeTopMovies(),
            top_characters: $this->computeTopCharacters(),
            top_referers: $this->computeTopReferers(),
            error_rates: $this->computeErrorRates(),
            cache_stats: $this->computeCacheStats(),
            requests_last_24h: $this->computeRequestsLast24Hours(),
            daily_breakdown: $this->computeDailyBreakdown(),
            computed_at: now()->toImmutable(),
        );
    }

    /**
     * Get the top 5 search queries with their percentages.
     *
     * @return array<QueryStatData>
     */
    protected function computeTopQueries(): array
    {
        $totalWithQuery = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->withQuery()
            ->count();

        if ($totalWithQuery === 0) {
            return [];
        }

        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->withQuery()
            ->select('query', DB::raw('COUNT(*) as count'))
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit($this->topLimit())
            ->get()
            ->map(fn ($row) => new QueryStatData(
                query: $row->query,
                count: (int) $row->count,
                percentage: round(($row->count / $totalWithQuery) * 100, 2),
            ))
            ->all();
    }

    /**
     * Get the average request duration in milliseconds.
     */
    protected function computeAverageDuration(): float
    {
        $average = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->avg('duration_ms');

        return round((float) $average, 2);
    }

    /**
     * Get the 95th percentile request duration.
     */
    protected function computeP95Duration(): float
    {
        $count = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->count();

        if ($count === 0) {
            return 0.0;
        }

        $offset = (int) floor($count * 0.95);

        $p95 = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->orderBy('duration_ms')
            ->offset(max(0, $offset - 1))
            ->limit(1)
            ->value('duration_ms');

        return round((float) $p95, 2);
    }

    /**
     * Get the most popular hour of day for search volume.
     */
    protected function computePeakHour(): ?HourlyStatData
    {
        $result = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->select(DB::raw('CAST(strftime("%H", created_at) AS INTEGER) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderByDesc('count')
            ->first();

        if ($result === null) {
            return null;
        }

        return new HourlyStatData(
            hour: (int) $result->hour,
            count: (int) $result->count,
        );
    }

    /**
     * Get the top 5 accessed movies.
     *
     * @return array<ResourceStatData>
     */
    protected function computeTopMovies(): array
    {
        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->movies()
            ->whereNotNull('resource_id')
            ->whereNotNull('resource_name')
            ->select('resource_id', 'resource_name', DB::raw('COUNT(*) as count'))
            ->groupBy('resource_id', 'resource_name')
            ->orderByDesc('count')
            ->limit($this->topLimit())
            ->get()
            ->map(fn ($row) => new ResourceStatData(
                id: (int) $row->resource_id,
                name: $row->resource_name,
                count: (int) $row->count,
            ))
            ->all();
    }

    /**
     * Get the top 5 accessed characters.
     *
     * @return array<ResourceStatData>
     */
    protected function computeTopCharacters(): array
    {
        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->people()
            ->whereNotNull('resource_id')
            ->whereNotNull('resource_name')
            ->select('resource_id', 'resource_name', DB::raw('COUNT(*) as count'))
            ->groupBy('resource_id', 'resource_name')
            ->orderByDesc('count')
            ->limit($this->topLimit())
            ->get()
            ->map(fn ($row) => new ResourceStatData(
                id: (int) $row->resource_id,
                name: $row->resource_name,
                count: (int) $row->count,
            ))
            ->all();
    }

    /**
     * Get the top 5 referers.
     *
     * @return array<RefererStatData>
     */
    protected function computeTopReferers(): array
    {
        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->select('referer', DB::raw('COUNT(*) as count'))
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit($this->topLimit())
            ->get()
            ->map(fn ($row) => new RefererStatData(
                referer: $row->referer,
                count: (int) $row->count,
            ))
            ->all();
    }

    /**
     * Get error rates by endpoint.
     *
     * @return array<EndpointErrorRateData>
     */
    protected function computeErrorRates(): array
    {
        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->select(
                'endpoint',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN is_error = 1 THEN 1 ELSE 0 END) as error_count')
            )
            ->groupBy('endpoint')
            ->get()
            ->map(fn ($row) => new EndpointErrorRateData(
                endpoint: $row->endpoint,
                total_requests: (int) $row->total_requests,
                error_count: (int) $row->error_count,
                error_rate: $row->total_requests > 0
                    ? round(($row->error_count / $row->total_requests) * 100, 2)
                    : 0.0,
            ))
            ->all();
    }

    /**
     * Get SWAPI cache hit/miss statistics.
     *
     * This sums all SWAPI API cache hits and misses across all requests.
     */
    protected function computeCacheStats(): CacheStatsData
    {
        $stats = RequestLog::query()
            ->withinDays($this->retentionDays())
            ->select(
                DB::raw('SUM(swapi_cache_hits) as hits'),
                DB::raw('SUM(swapi_cache_misses) as misses')
            )
            ->first();

        $hits = (int) ($stats->hits ?? 0);
        $misses = (int) ($stats->misses ?? 0);
        $total = $hits + $misses;

        return new CacheStatsData(
            hits: $hits,
            misses: $misses,
            total: $total,
            hit_rate: $total > 0 ? round(($hits / $total) * 100, 2) : 0.0,
        );
    }

    /**
     * Get the number of requests in the last 24 hours.
     */
    protected function computeRequestsLast24Hours(): int
    {
        return RequestLog::query()
            ->where('created_at', '>=', now()->subHours(24))
            ->count();
    }

    /**
     * Get daily request counts for the last 30 days.
     *
     * @return array<DailyRequestData>
     */
    protected function computeDailyBreakdown(): array
    {
        return RequestLog::query()
            ->withinDays($this->retentionDays())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => new DailyRequestData(
                date: $row->date,
                count: (int) $row->count,
            ))
            ->all();
    }
}
