<?php

namespace App\Data\Swapi\People;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class SearchPeopleResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        /** @var Collection<int, PersonResultData> */
        public Collection $result,
    ) {}
}
