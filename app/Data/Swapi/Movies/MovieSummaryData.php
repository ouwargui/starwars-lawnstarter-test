<?php

namespace App\Data\Swapi\Movies;

use App\Data\Swapi\People\PersonWithMoviesSummaryData;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class MovieSummaryDataProperties extends Data
{
    public function __construct(
        public string $title,
        public string $openingCrawl,
        /** @var Collection<int, PersonWithMoviesSummaryData> */
        public Collection $people,
    ) {}
}

final class MovieSummaryData extends Data
{
    public function __construct(
        public MovieSummaryDataProperties $movie,
    ) {}

    public static function fromMovieAndPeople(MovieData $movie, Collection $people): self
    {
        return new self(
            new MovieSummaryDataProperties(
                $movie->title,
                $movie->openingCrawl,
                $people,
            )
        );
    }
}
