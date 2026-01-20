<?php

namespace App\Data\Swapi\Movies;

use Spatie\LaravelData\Data;

final class GetMovieByIdResponseData extends Data
{
    public function __construct(
        public string $message,
        public string $apiVersion,
        public string $timestamp,
        public ?MovieResultData $result,
    ) {}
}
