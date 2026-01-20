<?php

namespace App\Data\Swapi;

use App\Data\Swapi\Movies\MovieResultData;
use App\Data\Swapi\Movies\SearchMoviesResponseData;
use App\Data\Swapi\People\PersonResultData;
use App\Data\Swapi\People\SearchPeopleResponseData;
use Spatie\LaravelData\Data;

final class FilterData extends Data
{
    public function __construct(
        public ?string $q,
        public ?string $type,
    ) {}
}

final class SearchResultData extends Data
{
    public function __construct(
        public string $name,
        public string $id,
    ) {}

    public static function fromPersonResultData(PersonResultData $model): self
    {
        return new self($model->properties->name, $model->uid);
    }

    public static function fromMovieResultData(MovieResultData $model): self
    {
        return new self($model->properties->title, $model->uid);
    }
}

final class SearchResponseData extends Data
{
    public function __construct(
        public FilterData $filters,
        public array $results,
    ) {}

    public static function fromFilters(?string $q, ?string $type): self
    {
        return new self(new FilterData($q, $type), []);
    }

    public static function fromSearchPeopleResponse(SearchPeopleResponseData $model, ?string $q, ?string $type): self
    {
        if (empty($model->result)) {
            return self::fromFilters($q, $type);
        }

        return new self(
            new FilterData($q, $type),
            $model->result
                ->toCollection()
                ->map(fn (PersonResultData $result) => SearchResultData::from($result))
                ->toArray()
        );
    }

    public static function fromSearchMoviesResponse(SearchMoviesResponseData $model, ?string $q, ?string $type): self
    {
        if (empty($model->result)) {
            return self::fromFilters($q, $type);
        }

        return new self(
            new FilterData($q, $type),
            $model->result
                ->toCollection()
                ->map(fn (MovieResultData $result) => SearchResultData::from($result))
                ->toArray()
        );
    }
}
