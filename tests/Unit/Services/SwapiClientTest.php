<?php

use App\Data\Swapi\Movies\GetMovieByIdResponseData;
use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\GetPersonByIdResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use App\Services\RequestContext;
use App\Services\SwapiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

describe('searchPeople', function () {
    it('returns search results for people', function () {
        Http::fake([
            '*/people*' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [
                    [
                        'description' => 'A person',
                        'uid' => '1',
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'birth_year' => '19BBY',
                            'eye_color' => 'blue',
                            'gender' => 'male',
                            'hair_color' => 'blond',
                            'height' => '172',
                            'mass' => '77',
                            'skin_color' => 'fair',
                            'homeworld' => 'https://swapi.tech/api/planets/1',
                            'films' => [],
                            'species' => [],
                            'starships' => [],
                            'vehicles' => [],
                            'url' => 'https://swapi.tech/api/people/1',
                            'created' => '2014-12-09T13:50:51.644000Z',
                            'edited' => '2014-12-20T21:17:56.891000Z',
                        ],
                    ],
                ],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $result = $client->searchPeople('Luke');

        expect($result)->toBeInstanceOf(SearchPeopleResponseData::class);
        expect($result->result)->not->toBeNull();
        expect($result->result->count())->toBe(1);
    });

    it('tracks cache miss on first request', function () {
        Http::fake([
            '*/people*' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $client->searchPeople('Luke');

        expect($context->swapiCacheMisses)->toBe(1);
        expect($context->swapiCacheHits)->toBe(0);
    });

    it('tracks cache hit on subsequent requests', function () {
        Http::fake([
            '*/people*' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $client->searchPeople('Luke');

        // Reset context for second request
        $context->reset();

        $client->searchPeople('Luke');

        expect($context->swapiCacheHits)->toBe(1);
        expect($context->swapiCacheMisses)->toBe(0);
    });
});

describe('searchMovies', function () {
    it('returns search results for movies', function () {
        Http::fake([
            '*/films*' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [
                    [
                        'description' => 'A movie',
                        'uid' => '1',
                        'properties' => [
                            'title' => 'A New Hope',
                            'episode_id' => 4,
                            'opening_crawl' => 'It is a period of civil war...',
                            'director' => 'George Lucas',
                            'producer' => 'Gary Kurtz',
                            'release_date' => '1977-05-25',
                            'species' => [],
                            'starships' => [],
                            'vehicles' => [],
                            'characters' => [],
                            'planets' => [],
                            'url' => 'https://swapi.tech/api/films/1',
                            'created' => '2014-12-10T14:23:31.880000Z',
                            'edited' => '2014-12-20T19:49:45.256000Z',
                        ],
                    ],
                ],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $result = $client->searchMovies('Hope');

        expect($result)->toBeInstanceOf(SearchMoviesResponseData::class);
        expect($result->result)->not->toBeNull();
        expect($result->result->count())->toBe(1);
    });
});

describe('getPerson', function () {
    it('returns person by id', function () {
        Http::fake([
            '*/people/1' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [
                    'description' => 'A person',
                    'uid' => '1',
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'birth_year' => '19BBY',
                        'eye_color' => 'blue',
                        'gender' => 'male',
                        'hair_color' => 'blond',
                        'height' => '172',
                        'mass' => '77',
                        'skin_color' => 'fair',
                        'homeworld' => 'https://swapi.tech/api/planets/1',
                        'films' => ['https://swapi.tech/api/films/1'],
                        'species' => [],
                        'starships' => [],
                        'vehicles' => [],
                        'url' => 'https://swapi.tech/api/people/1',
                        'created' => '2014-12-09T13:50:51.644000Z',
                        'edited' => '2014-12-20T21:17:56.891000Z',
                    ],
                ],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $result = $client->getPerson(1);

        expect($result)->toBeInstanceOf(GetPersonByIdResponseData::class);
        expect($result->result)->not->toBeNull();
        expect($result->result->properties->name)->toBe('Luke Skywalker');
    });
});

describe('getMovie', function () {
    it('returns movie by id', function () {
        Http::fake([
            '*/films/1' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [
                    'description' => 'A movie',
                    'uid' => '1',
                    'properties' => [
                        'title' => 'A New Hope',
                        'episode_id' => 4,
                        'opening_crawl' => 'It is a period of civil war...',
                        'director' => 'George Lucas',
                        'producer' => 'Gary Kurtz',
                        'release_date' => '1977-05-25',
                        'species' => [],
                        'starships' => [],
                        'vehicles' => [],
                        'characters' => ['https://swapi.tech/api/people/1'],
                        'planets' => [],
                        'url' => 'https://swapi.tech/api/films/1',
                        'created' => '2014-12-10T14:23:31.880000Z',
                        'edited' => '2014-12-20T19:49:45.256000Z',
                    ],
                ],
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);

        $result = $client->getMovie(1);

        expect($result)->toBeInstanceOf(GetMovieByIdResponseData::class);
        expect($result->result)->not->toBeNull();
        expect($result->result->properties->title)->toBe('A New Hope');
    });
});

describe('getIdFromUrl', function () {
    it('extracts id from valid url', function () {
        $context = new RequestContext;
        $client = new SwapiClient($context);

        expect($client->getIdFromUrl('https://swapi.tech/api/people/1'))->toBe(1);
        expect($client->getIdFromUrl('https://swapi.tech/api/films/4'))->toBe(4);
        expect($client->getIdFromUrl('https://swapi.tech/api/planets/123/'))->toBe(123);
    });

    it('returns null for invalid urls', function () {
        $context = new RequestContext;
        $client = new SwapiClient($context);

        expect($client->getIdFromUrl('https://swapi.tech/api/people/'))->toBeNull();
        expect($client->getIdFromUrl('https://swapi.tech/api/people/abc'))->toBeNull();
        expect($client->getIdFromUrl(''))->toBeNull();
    });
});
