<?php

namespace App\Http\Controllers;

use App\Services\SwapiService;
use Inertia\Inertia;

class MoviesController extends Controller
{
    public function __invoke(int $id, SwapiService $swapiService)
    {
        $movie = $swapiService->getMovieSummaryData($id);

        return Inertia::render('movies-page', $movie);
    }
}
