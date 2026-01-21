<?php

namespace App\Services;

use App\Data\Swapi\Movies\GetMovieByIdResponseData;
use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\GetPersonByIdResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * HTTP client for the Star Wars API (SWAPI).
 *
 * Handles all communication with the external SWAPI service including
 * caching, retry logic, and response transformation to Data objects.
 */
class SwapiClient
{
    protected PendingRequest $client;

    public function __construct(
        protected RequestContext $context,
    ) {
        $this->client = Http::baseUrl(config('services.swapi.base_url'))
            ->timeout(config('services.swapi.timeout'))
            ->retry(config('services.swapi.retry_attempts'), config('services.swapi.retry_delay'))
            ->acceptJson();
    }

    public function searchPeople(?string $q): SearchPeopleResponseData
    {
        $cacheKey = 'search_people:'.$q;

        Log::debug('Searching people', ['query' => $q]);

        return $this->cachedRequest($cacheKey, function () use ($q) {
            return SearchPeopleResponseData::from($this->get('people', ['name' => $q]));
        });
    }

    public function searchMovies(?string $q): SearchMoviesResponseData
    {
        $cacheKey = 'search_movies:'.$q;

        Log::debug('Searching movies', ['query' => $q]);

        return $this->cachedRequest($cacheKey, function () use ($q) {
            return SearchMoviesResponseData::from($this->get('films', ['title' => $q]));
        });
    }

    public function getPerson(int $id): GetPersonByIdResponseData
    {
        $cacheKey = 'person:'.$id;

        Log::debug('Fetching person', ['id' => $id]);

        return $this->cachedRequest($cacheKey, function () use ($id) {
            return GetPersonByIdResponseData::from($this->get('people/'.$id));
        });
    }

    public function getMovie(int $id): GetMovieByIdResponseData
    {
        $cacheKey = 'movie:'.$id;

        Log::debug('Fetching movie', ['id' => $id]);

        return $this->cachedRequest($cacheKey, function () use ($id) {
            return GetMovieByIdResponseData::from($this->get('films/'.$id));
        });
    }

    /**
     * Extract the numeric ID from a SWAPI resource URL.
     *
     * @example getIdFromUrl('https://swapi.tech/api/people/1') returns 1
     * @example getIdFromUrl('https://swapi.tech/api/films/4/') returns 4
     */
    public function getIdFromUrl(string $url): ?int
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = is_string($path) ? trim($path, '/') : '';
        $segments = $path === '' ? [] : explode('/', $path);
        $last = $segments === [] ? null : end($segments);

        return is_string($last) && ctype_digit($last) ? (int) $last : null;
    }

    /**
     * Execute a cached request while tracking SWAPI cache hits/misses.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    protected function cachedRequest(string $cacheKey, callable $callback): mixed
    {
        $cached = Cache::has($cacheKey);

        if ($cached) {
            $this->context->recordCacheHit();
            Log::debug('SWAPI cache hit', ['key' => $cacheKey]);
        } else {
            $this->context->recordCacheMiss();
            Log::debug('SWAPI cache miss', ['key' => $cacheKey]);
        }

        return Cache::rememberForever($cacheKey, $callback);
    }

    protected function get(string $endpoint, array $query = []): array
    {
        Log::debug('SWAPI request', ['endpoint' => $endpoint, 'query' => $query]);

        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->client->get($endpoint, $query);

        return $response->throw()->json();
    }
}
