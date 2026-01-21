<?php

namespace App\Data\Swapi\Movies;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class SearchMoviesResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        /** @var Collection<int, MovieResultData> */
        public Collection $result,
    ) {}
}
