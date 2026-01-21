<?php

use App\Data\Swapi\People\PersonSummaryData;
use App\Services\PeopleService;
use App\Services\RequestContext;
use App\Services\SwapiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    Cache::flush();
});

describe('getPersonSummaryData', function () {
    it('returns person summary with films', function () {
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
                        'characters' => [],
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
        $service = new PeopleService($client, $context);

        $result = $service->getPersonSummaryData(1);

        expect($result)->toBeInstanceOf(PersonSummaryData::class);
        expect($result->person->name)->toBe('Luke Skywalker');
        expect($result->person->films->count())->toBe(1);
    });

    it('sets context with person info', function () {
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
                        'films' => [],
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
        $service = new PeopleService($client, $context);

        $service->getPersonSummaryData(1);

        expect($context->resourceId)->toBe(1);
        expect($context->resourceName)->toBe('Luke Skywalker');
        expect($context->resourceType)->toBe('person');
    });

    it('throws NotFoundHttpException when person not found', function () {
        Http::fake([
            '*/people/999' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => null,
            ]),
        ]);

        $context = new RequestContext;
        $client = new SwapiClient($context);
        $service = new PeopleService($client, $context);

        $service->getPersonSummaryData(999);
    })->throws(NotFoundHttpException::class, 'Person not found.');

    it('handles empty films list', function () {
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
                        'films' => [],
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
        $service = new PeopleService($client, $context);

        $result = $service->getPersonSummaryData(1);

        expect($result->person->films->count())->toBe(0);
    });
});
