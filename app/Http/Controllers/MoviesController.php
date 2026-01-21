<?php

namespace App\Http\Controllers;

use App\Services\MoviesService;
use Inertia\Inertia;

class MoviesController extends Controller
{
    public function __invoke(int $id, MoviesService $moviesService)
    {
        $movie = $moviesService->getMovieSummaryData($id);

        return Inertia::render('movies-page', $movie);
    }
}
