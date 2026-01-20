<?php

namespace App\Services;

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

    public function searchPeople(array $query): SearchPeopleResponseData
    {
        return SearchPeopleResponseData::from($this->get('people', $query));
    }

    public function searchMovies(array $query): array
    {
        return $this->get('films', $query);
    }

    protected function get(string $endpoint, array $query = []): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->client->get($endpoint, $query);

        return $response->throw()->json();
    }
}
