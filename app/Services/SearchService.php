<?php

namespace App\Services;

use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Illuminate\Support\Facades\Log;

/**
 * Service for searching Star Wars resources.
 */
class SearchService
{
    public function __construct(
        protected SwapiClient $client,
    ) {}

    /**
     * Search for people or movies by query string.
     *
     * @param  string  $type  Must be 'people' or 'movies'. Other values throw UnhandledMatchError.
     *
     * @throws \UnhandledMatchError If type is not 'people' or 'movies'
     */
    public function search(string $q, string $type): SearchPeopleResponseData|SearchMoviesResponseData
    {
        Log::info('Search request', ['query' => $q, 'type' => $type]);

        $result = match ($type) {
            'people' => $this->client->searchPeople($q),
            'movies' => $this->client->searchMovies($q),
        };

        $resultCount = $result->result?->count() ?? 0;

        Log::info('Search completed', [
            'query' => $q,
            'type' => $type,
            'results_count' => $resultCount,
        ]);

        return $result;
    }
}
