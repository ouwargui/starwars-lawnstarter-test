<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MoviesController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('movies-page');
    }
}
