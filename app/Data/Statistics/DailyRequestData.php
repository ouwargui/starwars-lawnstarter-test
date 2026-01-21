<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for daily request counts.
 */
final class DailyRequestData extends Data
{
    public function __construct(
        public readonly string $date,
        public readonly int $count,
    ) {}
}
