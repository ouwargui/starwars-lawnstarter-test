<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class PersonSummaryDataProperties extends Data
{
    public function __construct(
        public string $name,
        public string $birthYear,
        public string $eyeColor,
        public string $gender,
        public string $hairColor,
        public string $height,
        public string $mass,
        #[DataCollectionOf(PersonWithMoviesSummaryData::class)]
        public DataCollection $films,
    ) {}
}

final class PersonSummaryData extends Data
{
    public function __construct(
        public PersonSummaryDataProperties $person,
    ) {}

    public static function fromPersonAndMovies(PersonData $person, DataCollection $films): self
    {
        return new self(
            new PersonSummaryDataProperties(
                $person->name,
                $person->birthYear,
                $person->eyeColor,
                $person->gender,
                $person->hairColor,
                $person->height,
                $person->mass,
                $films,
            )
        );
    }
}
