<?php

namespace App\Services;

use App\Data\Swapi\Movies\MovieSummaryData;
use App\Data\Swapi\Movies\MovieWithPeopleSummaryData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Service for fetching and transforming movie data from SWAPI.
 */
class MoviesService
{
    public function __construct(
        protected SwapiClient $client,
        protected RequestContext $context,
    ) {}

    public function getMovieSummaryData(int $id): MovieSummaryData
    {
        Log::info('Fetching movie summary', ['id' => $id]);

        $movieResponse = $this->client->getMovie($id);

        if (! $movieResponse->result) {
            Log::warning('Movie not found', ['id' => $id]);
            throw new NotFoundHttpException('Movie not found.');
        }

        $movie = $movieResponse->result->properties;

        $this->context->resourceId = $id;
        $this->context->resourceName = $movie->title;
        $this->context->resourceType = 'movie';

        $people = $this->getPeopleSummary($movie->characters);

        Log::info('Movie summary fetched', [
            'id' => $id,
            'title' => $movie->title,
            'characters_count' => $people->count(),
        ]);

        return MovieSummaryData::fromMovieAndPeople($movie, $people);
    }

    protected function getPeopleSummary(Collection $people): Collection
    {
        if ($people->isEmpty()) {
            return collect();
        }

        return $people
            ->map(fn (string $person) => $this->client->getIdFromUrl($person))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->client->getPerson($id))
            ->filter(fn ($person) => $person->result !== null)
            ->map(fn ($person) => MovieWithPeopleSummaryData::fromPersonResultData($person->result))
            ->values();
    }
}
