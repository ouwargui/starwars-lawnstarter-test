<?php

namespace App\Data\Swapi\Movies;

use Spatie\LaravelData\Data;

final class MovieResultData extends Data
{
    public function __construct(
        public string $description,
        public string $uid,
        public MovieData $properties
    ) {}
}
