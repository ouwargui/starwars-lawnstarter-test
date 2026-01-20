<?php

namespace App\Http\Controllers;

use App\Data\Swapi\People\PersonPageData;
use App\Services\SwapiService;
use Inertia\Inertia;

class PeopleController extends Controller
{
    public function __invoke(int $id, SwapiService $swapiService)
    {
        $person = $swapiService->getPersonById($id);

        return Inertia::render('people-page', PersonPageData::from($person));
    }
}
