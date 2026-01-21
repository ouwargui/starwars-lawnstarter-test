<?php

namespace App\Services;

use App\Data\Swapi\Movies\GetMovieByIdResponseData;
use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\GetPersonByIdResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SwapiClient
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
        $cacheKey = 'search_people:'.$q;

        return Cache::rememberForever($cacheKey, function () use ($q) {
            return SearchPeopleResponseData::from($this->get('people', ['name' => $q]));
        });
    }

    public function searchMovies(?string $q): SearchMoviesResponseData
    {
        $cacheKey = 'search_movies:'.$q;

        return Cache::rememberForever($cacheKey, function () use ($q) {
            return SearchMoviesResponseData::from($this->get('films', ['title' => $q]));
        });
    }

    public function getPerson(int $id): GetPersonByIdResponseData
    {
        $cacheKey = 'person:'.$id;

        return Cache::rememberForever($cacheKey, function () use ($id) {
            return GetPersonByIdResponseData::from($this->get('people/'.$id));
        });
    }

    public function getMovie(int $id): GetMovieByIdResponseData
    {
        $cacheKey = 'movie:'.$id;

        return Cache::rememberForever($cacheKey, function () use ($id) {
            return GetMovieByIdResponseData::from($this->get('films/'.$id));
        });
    }

    public function getIdFromUrl(string $url): ?int
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = is_string($path) ? trim($path, '/') : '';
        $segments = $path === '' ? [] : explode('/', $path);
        $last = $segments === [] ? null : end($segments);

        return is_string($last) && ctype_digit($last) ? (int) $last : null;
    }

    protected function get(string $endpoint, array $query = []): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->client->get($endpoint, $query);

        return $response->throw()->json();
    }
}
