<?php

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

beforeEach(function () {
    Http::preventStrayRequests();
    Http::fake([
        '127.0.0.1:*/render' => Http::response(['head' => [], 'body' => '']),
    ]);
});

describe('search page', function () {
    it('renders the search page without query parameters', function () {
        /** @var TestCase $this */
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('search-page'));
    });

    it('renders search results for people', function () {
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

        /** @var TestCase $this */
        $this->get('/?q=Luke&type=people')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('search-page')
                ->has('filters')
                ->has('results')
            );
    });

    it('renders search results for movies', function () {
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

        /** @var TestCase $this */
        $this->get('/?q=Hope&type=movies')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('search-page')
                ->has('filters')
                ->has('results')
            );
    });

    it('handles empty search results', function () {
        Http::fake([
            '*/people*' => Http::response([
                'message' => 'ok',
                'apiVersion' => '1.0',
                'timestamp' => '2024-01-01T00:00:00.000Z',
                'result' => [],
            ]),
        ]);

        /** @var TestCase $this */
        $this->get('/?q=NonexistentCharacter&type=people')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('search-page')
                ->where('results', [])
            );
    });
});
