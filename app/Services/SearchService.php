<?php

namespace App\Services;

use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;

class SearchService
{
    public function __construct(
        protected SwapiClient $client,
    ) {}

    public function search(string $q, string $type): SearchPeopleResponseData|SearchMoviesResponseData
    {
        return match ($type) {
            'people' => $this->client->searchPeople($q),
            'movies' => $this->client->searchMovies($q),
        };
    }
}
