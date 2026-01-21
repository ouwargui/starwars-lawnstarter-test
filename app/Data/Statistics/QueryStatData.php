<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for a query's statistics.
 */
final class QueryStatData extends Data
{
    public function __construct(
        public readonly string $query,
        public readonly int $count,
        public readonly float $percentage,
    ) {}
}
