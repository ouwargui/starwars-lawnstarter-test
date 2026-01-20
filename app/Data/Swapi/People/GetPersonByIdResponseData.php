<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Data;

final class GetPersonByIdResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        public ?PersonResultData $result,
    ) {}
}
