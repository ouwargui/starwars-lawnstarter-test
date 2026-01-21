<?php

namespace App\Data\Swapi\People;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

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
        /** @var Collection<int, PersonWithMoviesSummaryData> */
        public Collection $films,
    ) {}
}

final class PersonSummaryData extends Data
{
    public function __construct(
        public PersonSummaryDataProperties $person,
    ) {}

    public static function fromPersonAndMovies(PersonData $person, Collection $films): self
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
