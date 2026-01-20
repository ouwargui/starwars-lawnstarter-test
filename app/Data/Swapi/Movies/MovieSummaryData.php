<?php

namespace App\Data\Swapi\Movies;

use Spatie\LaravelData\Data;

final class MovieSummaryData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
    ) {}

    public static function fromMovieResultData(MovieResultData $movie): self
    {
        return new self((int) $movie->uid, $movie->properties->title);
    }
}
