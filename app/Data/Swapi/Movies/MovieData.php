<?php

namespace App\Data\Swapi\Movies;

use App\Data\Casts\AsCollection;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class MovieData extends Data
{
    public function __construct(
        public string $title,

        #[MapInputName('episode_id')]
        public int $episodeId,

        #[MapInputName('opening_crawl')]
        public string $openingCrawl,

        public string $director,
        public string $producer,

        #[MapInputName('release_date')]
        public string $releaseDate,

        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $species,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $starships,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $vehicles,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $characters,
        /** @var Collection<int, string> */
        #[WithCast(AsCollection::class)]
        public Collection $planets,
        public string $url,
        public string $created,
        public string $edited,
    ) {}
}
