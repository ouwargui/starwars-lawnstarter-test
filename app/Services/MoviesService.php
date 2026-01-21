<?php

namespace App\Services;

use App\Data\Swapi\Movies\MovieSummaryData;
use App\Data\Swapi\Movies\MovieWithPeopleSummaryData;
use Spatie\LaravelData\DataCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MoviesService
{
    public function __construct(
        protected SwapiClient $client,
    ) {}

    public function getMovieSummaryData(int $id): MovieSummaryData
    {
        $movieResponse = $this->client->getMovie($id);

        if (! $movieResponse->result) {
            throw new NotFoundHttpException('Movie not found.');
        }

        $movie = $movieResponse->result->properties;
        $people = $this->getPeopleSummary($movie->characters);

        return MovieSummaryData::fromMovieAndPeople($movie, $people);
    }

    protected function getPeopleSummary(array $people): DataCollection
    {
        if (empty($people)) {
            return new DataCollection(MovieWithPeopleSummaryData::class, []);
        }

        $summaries = collect($people)
            ->map(fn (string $person) => $this->client->getIdFromUrl($person))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->client->getPerson($id))
            ->filter(fn ($person) => $person->result !== null)
            ->map(fn ($person) => MovieWithPeopleSummaryData::fromPersonResultData($person->result))
            ->values()
            ->all();

        return new DataCollection(MovieWithPeopleSummaryData::class, $summaries);
    }
}
