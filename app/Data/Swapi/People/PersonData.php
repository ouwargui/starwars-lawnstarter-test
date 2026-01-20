<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class PersonData extends Data
{
    public function __construct(
        public string $name,

        #[MapInputName('birth_year')]
        public string $birthYear,

        #[MapInputName('eye_color')]
        public string $eyeColor,
        public string $gender,

        #[MapInputName('hair_color')]
        public string $hairColor,
        public string $height,
        public string $mass,

        #[MapInputName('skin_color')]
        public string $skinColor,
        public string $homeworld,
        public array $films,
        public ?array $species,
        public ?array $starships,
        public ?array $vehicles,
        public string $url,
        public string $created,
        public string $edited,
    ) {}
}
