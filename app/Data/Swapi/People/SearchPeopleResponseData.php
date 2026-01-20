<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class SearchPeopleResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        #[DataCollectionOf(PersonResultData::class)]
        public ?DataCollection $result,
    ) {}
}
