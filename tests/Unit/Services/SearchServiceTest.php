<?php

use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use App\Services\RequestContext;
use App\Services\SearchService;
use App\Services\SwapiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

describe('search', function () {
    it('searches people when type is people', function () {
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
        $service = new SearchService($client);

        $result = $service->search('Luke', 'people');

        expect($result)->toBeInstanceOf(SearchPeopleResponseData::class);
        expect($result->result->count())->toBe(1);
    });

    it('searches movies when type is movies', function () {
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
        $service = new SearchService($client);

        $result = $service->search('Hope', 'movies');

        expect($result)->toBeInstanceOf(SearchMoviesResponseData::class);
        expect($result->result->count())->toBe(1);
    });

    it('throws error for invalid type', function () {
        $context = new RequestContext;
        $client = new SwapiClient($context);
        $service = new SearchService($client);

        $service->search('test', 'invalid');
    })->throws(UnhandledMatchError::class);

    it('returns empty results when no matches', function () {
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
        $service = new SearchService($client);

        $result = $service->search('NonexistentName', 'people');

        expect($result)->toBeInstanceOf(SearchPeopleResponseData::class);
        expect($result->result->count())->toBe(0);
    });
});
