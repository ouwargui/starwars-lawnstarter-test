<?php

namespace App\Services;

use App\Data\Swapi\People\PersonSummaryData;
use App\Data\Swapi\People\PersonWithMoviesSummaryData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Service for fetching and transforming people data from SWAPI.
 */
class PeopleService
{
    public function __construct(
        protected SwapiClient $client,
        protected RequestContext $context,
    ) {}

    public function getPersonSummaryData(int $id): PersonSummaryData
    {
        Log::info('Fetching person summary', ['id' => $id]);

        $personResponse = $this->client->getPerson($id);

        if (! $personResponse->result) {
            Log::warning('Person not found', ['id' => $id]);
            throw new NotFoundHttpException('Person not found.');
        }

        $person = $personResponse->result->properties;

        $this->context->resourceId = $id;
        $this->context->resourceName = $person->name;
        $this->context->resourceType = 'person';

        $films = $this->getMoviesSummary($person->films);

        Log::info('Person summary fetched', [
            'id' => $id,
            'name' => $person->name,
            'films_count' => $films->count(),
        ]);

        return PersonSummaryData::fromPersonAndMovies($person, $films);
    }

    protected function getMoviesSummary(Collection $films): Collection
    {
        if ($films->isEmpty()) {
            return collect();
        }

        return $films
            ->map(fn (string $film) => $this->client->getIdFromUrl($film))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->client->getMovie($id))
            ->filter(fn ($movie) => $movie->result !== null)
            ->map(fn ($movie) => PersonWithMoviesSummaryData::fromMovieResultData($movie->result))
            ->values();
    }
}
