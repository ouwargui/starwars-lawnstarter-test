<?php

namespace App\Http\Controllers;

use App\Services\PeopleService;
use Inertia\Inertia;

class PeopleController extends Controller
{
    public function __invoke(int $id, PeopleService $peopleService)
    {
        $person = $peopleService->getPersonSummaryData($id);

        return Inertia::render('people-page', $person);
    }
}
