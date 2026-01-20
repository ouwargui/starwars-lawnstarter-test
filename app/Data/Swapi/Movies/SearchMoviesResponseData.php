<?php

namespace App\Data\Swapi\Movies;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class SearchMoviesResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        #[DataCollectionOf(MovieResultData::class)]
        public ?DataCollection $result,
    ) {}
}
