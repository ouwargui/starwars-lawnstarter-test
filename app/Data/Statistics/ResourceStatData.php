<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for a resource's access statistics.
 */
final class ResourceStatData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $count,
    ) {}
}
