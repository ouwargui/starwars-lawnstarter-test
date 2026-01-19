<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $type = $request->query('type');

        $randomSize = rand(1, 6);
        $hasSearch = !empty($search);
        $results = $hasSearch ? collect(range(1, $randomSize))->map(function ($index) {
            return 'result' . $index;
        })->toArray() : null;

        return Inertia::render('search-page', [
            'filters' => [
                'q' => $search,
                'type' => $type,
            ],
            'results' => $results,
        ]);
    }
}
