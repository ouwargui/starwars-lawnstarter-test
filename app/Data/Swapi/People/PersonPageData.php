<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Data;

final class PersonPageDataProperties extends Data
{
    public function __construct(
        public string $name,
        public string $birthYear,
        public string $eyeColor,
        public string $gender,
        public string $hairColor,
        public string $height,
        public string $mass,
        public array $films,
    ) {}
}

final class PersonPageData extends Data
{
    public function __construct(
        public PersonPageDataProperties $person,
    ) {}

    public static function fromGetPersonByIdResponseData(GetPersonByIdResponseData $data): self
    {
        return new self(
            new PersonPageDataProperties(
                $data->result->properties->name,
                $data->result->properties->birthYear,
                $data->result->properties->eyeColor,
                $data->result->properties->gender,
                $data->result->properties->hairColor,
                $data->result->properties->height,
                $data->result->properties->mass,
                $data->result->properties->films,
            )
        );
    }
}
