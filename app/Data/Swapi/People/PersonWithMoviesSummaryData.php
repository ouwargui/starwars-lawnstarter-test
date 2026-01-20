<?php

namespace App\Data\Swapi\People;

use App\Data\Swapi\Movies\MovieResultData;
use Spatie\LaravelData\Data;

final class PersonWithMoviesSummaryData extends Data
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
