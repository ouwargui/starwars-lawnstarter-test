<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PeopleController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('people-page');
    }
}
