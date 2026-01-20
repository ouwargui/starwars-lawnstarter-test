<?php

namespace App\Services;

use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\GetPersonByIdResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SwapiService
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.swapi.base_url'))
            ->timeout(config('services.swapi.timeout'))
            ->retry(config('services.swapi.retry_attempts'), config('services.swapi.retry_delay'))
            ->acceptJson();
    }

    public function searchPeople(?string $q): SearchPeopleResponseData
    {
        return SearchPeopleResponseData::from($this->get('people', ['name' => $q]));
    }

    public function getPersonById(int $id): GetPersonByIdResponseData
    {
        return GetPersonByIdResponseData::from($this->get('people/'.$id));
    }

    public function searchMovies(?string $q): SearchMoviesResponseData
    {
        return SearchMoviesResponseData::from($this->get('films', ['title' => $q]));
    }

    protected function get(string $endpoint, array $query = []): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->client->get($endpoint, $query);

        return $response->throw()->json();
    }
}
