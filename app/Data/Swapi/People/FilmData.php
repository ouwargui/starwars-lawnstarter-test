<?php

namespace App\Data\Swapi\People;

use Spatie\LaravelData\Data;

final class FilmData extends Data
{
    public function __construct(
        public string $title,
        public int $episode_id,
        public string $opening_crawl,
        public string $director,
        public string $producer,
        public string $release_date,
        public array $species,
        public array $starships,
        public array $vehicles,
        public array $characters,
        public array $planets,
        public string $url,
        public string $created,
        public string $edited,
    ) {}

    public static function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'episode_id' => ['required', 'integer'],
            'opening_crawl' => ['required', 'string'],
            'director' => ['required', 'string'],
            'producer' => ['required', 'string'],
            'release_date' => ['required', 'date'],
            'species' => ['required', 'array'],
            'species.*' => ['url'],
            'starships' => ['required', 'array'],
            'starships.*' => ['url'],
            'vehicles' => ['required', 'array'],
            'vehicles.*' => ['url'],
            'characters' => ['required', 'array'],
            'characters.*' => ['url'],
            'planets' => ['required', 'array'],
            'planets.*' => ['url'],
            'url' => ['required', 'url'],
            'created' => ['required', 'date'],
            'edited' => ['required', 'date'],
        ];
    }
}
