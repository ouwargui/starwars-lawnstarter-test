<?php

namespace App\Http\Controllers;

use App\Data\Statistics\StatisticsData;
use App\Jobs\ComputeStatisticsJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

final class StatisticsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $statistics = Cache::get(ComputeStatisticsJob::CACHE_KEY);

        if ($statistics === null) {
            return response()->json(StatisticsData::default());
        }

        return response()->json($statistics);
    }
}
