<?php

namespace App\Jobs;

use App\Models\RequestLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Job that prunes old request logs for data retention.
 *
 * Deletes records older than 30 days to keep the database lean.
 * Runs daily via the scheduler.
 */
final class PruneRequestLogsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Number of days to retain request logs.
     */
    private const RETENTION_DAYS = 30;

    /**
     * Chunk size for deleting records to avoid memory issues.
     */
    private const CHUNK_SIZE = 1000;

    public function handle(): void
    {
        Log::info('Pruning old request logs...', [
            'retention_days' => self::RETENTION_DAYS,
        ]);

        $cutoffDate = now()->subDays(self::RETENTION_DAYS);

        $deletedCount = 0;

        do {
            $deleted = RequestLog::query()
                ->where('created_at', '<', $cutoffDate)
                ->limit(self::CHUNK_SIZE)
                ->delete();

            $deletedCount += $deleted;
        } while ($deleted === self::CHUNK_SIZE);

        Log::info('Request logs pruned successfully.', [
            'deleted_count' => $deletedCount,
        ]);
    }
}
