<?php

namespace App\Http\Controllers;

use App\Data\Swapi\SearchResponseData;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request, SearchService $searchService)
    {
        $q = $request->input('q');
        $type = $request->input('type');

        if (empty($q) || empty($type)) {
            return Inertia::render('search-page', SearchResponseData::fromFilters($q, $type));
        }

        $results = $searchService->search($q, $type);

        return Inertia::render('search-page', SearchResponseData::from($results, $q, $type));
    }
}
