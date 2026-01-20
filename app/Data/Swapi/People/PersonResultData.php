<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Data;

final class PersonResultData extends Data
{
    public function __construct(
        public string $description,
        public string $uid,
        public PersonData $properties
    ) {}
}
