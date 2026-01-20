<?php

namespace App\Data\Swapi\Movies;

use App\Data\Swapi\People\PersonResultData;
use Spatie\LaravelData\Data;

final class MovieWithPeopleSummaryData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function fromPersonResultData(PersonResultData $person): self
    {
        return new self((int) $person->uid, $person->properties->name);
    }
}
