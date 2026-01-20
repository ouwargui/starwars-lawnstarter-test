<?php

namespace App\Data\Swapi\Movies;

use Spatie\LaravelData\Attributes\MapInputName;
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

        public array $species,
        public array $starships,
        public array $vehicles,
        public array $characters,
        public array $planets,
        public string $url,
        public string $created,
        public string $edited,
    ) {}
}
