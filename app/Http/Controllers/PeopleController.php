<?php

namespace App\Http\Controllers;

use App\Services\SwapiService;
use Inertia\Inertia;

class PeopleController extends Controller
{
    public function __invoke(int $id, SwapiService $swapiService)
    {
        $person = $swapiService->getPersonSummaryData($id);

        return Inertia::render('people-page', $person);
    }
}
