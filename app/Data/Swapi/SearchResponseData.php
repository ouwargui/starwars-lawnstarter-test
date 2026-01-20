<?php

namespace App\Data\Swapi;

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
    ) {}

    public static function fromPersonResultData(PersonResultData $model): self
    {
        return new self($model->properties->name);
    }
}

final class SearchResponseData extends Data
{
    public function __construct(
        public FilterData $filter,
        public array $results,
    ) {}

    public static function fromSearchPeopleResponse(SearchPeopleResponseData $model, ?string $q, ?string $type): self
    {
        if (empty($model->result)) {
            return new self(new FilterData($q, $type), []);
        }

        return new self(
            new FilterData($q, $type),
            $model->result
                ->toCollection()
                ->map(fn (PersonResultData $result) => SearchResultData::from($result))
                ->toArray()
        );
    }

    // public static function fromSearchMoviesResponse(SearchMoviesResponseData $model): self
    // {
    //     return new self([
    //         'results' => $model->result->toCollection()->map(fn (MovieResultData $result) => $result->properties->title)->toArray(),
    //     ]);
    // }
}
