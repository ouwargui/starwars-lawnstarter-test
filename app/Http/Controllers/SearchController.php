<?php

namespace App\Http\Controllers;

use App\Data\Swapi\SearchResponseData;
use App\Services\SwapiService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request, SwapiService $swapiService)
    {
        $q = $request->input('q');
        $type = $request->input('type');

        if (empty($q) || empty($type)) {
            return Inertia::render('search-page', SearchResponseData::fromFilters($q, $type));
        }

        $results = match ($type) {
            'people' => $swapiService->searchPeople($q),
            'movies' => $swapiService->searchMovies($q),
        };

        return Inertia::render('search-page', SearchResponseData::from($results, $q, $type));
    }
}
