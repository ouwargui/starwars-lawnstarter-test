<?php

namespace App\Services;

use App\Data\Swapi\Movies\GetMovieByIdResponseData;
use App\Data\Swapi\Movies\MovieSummaryData;
use App\Data\Swapi\Movies\MovieWithPeopleSummaryData;
use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\GetPersonByIdResponseData;
use App\Data\Swapi\People\PersonSummaryData;
use App\Data\Swapi\People\PersonWithMoviesSummaryData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $cacheKey = 'search_people:'.$q;

        return Cache::rememberForever($cacheKey, function () use ($q) {
            return SearchPeopleResponseData::from($this->get('people', ['name' => $q]));
        });
    }

    public function getPersonById(int $id): GetPersonByIdResponseData
    {
        $cacheKey = 'person:'.$id;

        return Cache::rememberForever($cacheKey, function () use ($id) {
            return GetPersonByIdResponseData::from($this->get('people/'.$id));
        });
    }

    public function getPersonSummaryData(int $id): PersonSummaryData
    {
        $personResponse = $this->getPersonById($id);

        if (! $personResponse->result) {
            throw new NotFoundHttpException('Person not found.');
        }

        $person = $personResponse->result->properties;
        $films = $this->getMoviesSummary($person->films);

        return PersonSummaryData::fromPersonAndMovies($person, $films);
    }

    public function searchMovies(?string $q): SearchMoviesResponseData
    {
        $cacheKey = 'search_movies:'.$q;

        return Cache::rememberForever($cacheKey, function () use ($q) {
            return SearchMoviesResponseData::from($this->get('films', ['title' => $q]));
        });
    }

    public function getMovieById(int $id): GetMovieByIdResponseData
    {
        $cacheKey = 'movie:'.$id;

        return Cache::rememberForever($cacheKey, function () use ($id) {
            return GetMovieByIdResponseData::from($this->get('films/'.$id));
        });
    }

    public function getMovieSummaryData(int $id): MovieSummaryData
    {
        $movieResponse = $this->getMovieById($id);

        if (! $movieResponse->result) {
            throw new NotFoundHttpException('Movie not found.');
        }

        $movie = $movieResponse->result->properties;
        $people = $this->getPeopleSummary($movie->characters);

        return MovieSummaryData::fromMovieAndPeople($movie, $people);
    }

    protected function get(string $endpoint, array $query = []): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->client->get($endpoint, $query);

        return $response->throw()->json();
    }

    protected function getPeopleSummary(array $people): DataCollection
    {
        if (empty($people)) {
            return new DataCollection(PersonSummaryData::class, []);
        }

        $summaries = collect($people)
            ->map(fn (string $person) => $this->getIdFromUrl($person))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->getPersonById($id))
            ->filter(fn (GetPersonByIdResponseData $person) => $person->result !== null)
            ->map(fn (GetPersonByIdResponseData $person) => MovieWithPeopleSummaryData::fromPersonResultData($person->result))
            ->values()
            ->all();

        return new DataCollection(MovieWithPeopleSummaryData::class, $summaries);
    }

    protected function getMoviesSummary(array $films): DataCollection
    {
        if (empty($films)) {
            return new DataCollection(PersonWithMoviesSummaryData::class, []);
        }

        $summaries = collect($films)
            ->map(fn (string $film) => $this->getIdFromUrl($film))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->getMovieById($id))
            ->filter(fn (GetMovieByIdResponseData $movie) => $movie->result !== null)
            ->map(fn (GetMovieByIdResponseData $movie) => PersonWithMoviesSummaryData::fromMovieResultData($movie->result))
            ->values()
            ->all();

        return new DataCollection(PersonWithMoviesSummaryData::class, $summaries);
    }

    protected function getIdFromUrl(string $url): ?int
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = is_string($path) ? trim($path, '/') : '';
        $segments = $path === '' ? [] : explode('/', $path);
        $last = $segments === [] ? null : end($segments);

        return is_string($last) && ctype_digit($last) ? (int) $last : null;
    }
}
