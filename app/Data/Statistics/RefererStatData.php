<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for a referer's statistics.
 */
final class RefererStatData extends Data
{
    public function __construct(
        public readonly string $referer,
        public readonly int $count,
    ) {}
}
