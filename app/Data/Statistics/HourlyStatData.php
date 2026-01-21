<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for the most popular hour.
 */
final class HourlyStatData extends Data
{
    public function __construct(
        public readonly int $hour,
        public readonly int $count,
    ) {}
}
