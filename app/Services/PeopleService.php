<?php

namespace App\Services;

use App\Data\Swapi\People\PersonSummaryData;
use App\Data\Swapi\People\PersonWithMoviesSummaryData;
use Spatie\LaravelData\DataCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PeopleService
{
    public function __construct(
        protected SwapiClient $client,
    ) {}

    public function getPersonSummaryData(int $id): PersonSummaryData
    {
        $personResponse = $this->client->getPerson($id);

        if (! $personResponse->result) {
            throw new NotFoundHttpException('Person not found.');
        }

        $person = $personResponse->result->properties;
        $films = $this->getMoviesSummary($person->films);

        return PersonSummaryData::fromPersonAndMovies($person, $films);
    }

    protected function getMoviesSummary(array $films): DataCollection
    {
        if (empty($films)) {
            return new DataCollection(PersonWithMoviesSummaryData::class, []);
        }

        $summaries = collect($films)
            ->map(fn (string $film) => $this->client->getIdFromUrl($film))
            ->filter(fn (?int $id) => $id !== null)
            ->map(fn (int $id) => $this->client->getMovie($id))
            ->filter(fn ($movie) => $movie->result !== null)
            ->map(fn ($movie) => PersonWithMoviesSummaryData::fromMovieResultData($movie->result))
            ->values()
            ->all();

        return new DataCollection(PersonWithMoviesSummaryData::class, $summaries);
    }
}
