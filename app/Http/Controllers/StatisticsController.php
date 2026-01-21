<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;

final class StatisticsController extends Controller
{
    public function __invoke(StatisticsService $statisticsService): JsonResponse
    {
        return response()
            ->json($statisticsService->getStatistics());
    }
}
