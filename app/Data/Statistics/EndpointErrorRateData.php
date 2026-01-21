<?php

namespace App\Data\Statistics;

use Spatie\LaravelData\Data;

/**
 * Data object for an endpoint's error rate.
 */
final class EndpointErrorRateData extends Data
{
    public function __construct(
        public readonly string $endpoint,
        public readonly int $total_requests,
        public readonly int $error_count,
        public readonly float $error_rate,
    ) {}
}
