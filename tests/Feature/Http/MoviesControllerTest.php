<?php

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

beforeEach(function () {
    Http::preventStrayRequests();
    Http::fake([
        '127.0.0.1:*/render' => Http::response(['head' => [], 'body' => '']),
    ]);
});

describe('movies page', function () {
    it('renders movie details page', function () {
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
                        'characters' => [],
                        'planets' => [],
                        'url' => 'https://swapi.tech/api/films/1',
                        'created' => '2014-12-10T14:23:31.880000Z',
                        'edited' => '2014-12-20T19:49:45.256000Z',
                    ],
                ],
            ]),
        ]);

        /** @var TestCase $this */
        $this->get('/movies/1')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('movies-page')
                ->has('movie')
            );
    });

    it('renders movie with characters', function () {
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

        /** @var TestCase $this */
        $this->get('/movies/1')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('movies-page')
                ->has('movie.people', 1)
            );
    });

    it('returns 404 for non-existent movie', function () {
        Http::fake([
            '*/films/999' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => null,
            ]),
        ]);

        /** @var TestCase $this */
        $this->get('/movies/999')->assertNotFound();
    });
});
