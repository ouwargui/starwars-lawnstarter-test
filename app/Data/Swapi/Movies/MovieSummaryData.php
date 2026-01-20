<?php

namespace App\Data\Swapi\Movies;

use App\Data\Swapi\People\PersonWithMoviesSummaryData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class MovieSummaryDataProperties extends Data
{
    public function __construct(
        public string $title,
        public string $openingCrawl,
        #[DataCollectionOf(PersonWithMoviesSummaryData::class)]
        public DataCollection $people,
    ) {}
}

final class MovieSummaryData extends Data
{
    public function __construct(
        public MovieSummaryDataProperties $movie,
    ) {}

    public static function fromMovieAndPeople(MovieData $movie, DataCollection $people): self
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
