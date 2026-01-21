<?php

namespace App\Data\Swapi\People;

use App\Data\Casts\AsCollection;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
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

        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $films,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public ?Collection $species,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $starships,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $vehicles,
        public string $url,
        public string $created,
        public string $edited,
    ) {}
}
