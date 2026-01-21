<?php

namespace App\Jobs;

use App\Models\RequestLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Job that prunes old request logs for data retention.
 *
 * Deletes records older than the configured retention period to keep
 * the database lean. Runs daily via the scheduler.
 */
final class PruneRequestLogsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $retentionDays = config('statistics.retention_days');
        $chunkSize = config('statistics.prune_chunk_size');

        Log::info('Pruning old request logs...', [
            'retention_days' => $retentionDays,
        ]);

        $cutoffDate = now()->subDays($retentionDays);

        $deletedCount = 0;

        do {
            $deleted = RequestLog::query()
                ->where('created_at', '<', $cutoffDate)
                ->limit($chunkSize)
                ->delete();

            $deletedCount += $deleted;
        } while ($deleted === $chunkSize);

        Log::info('Request logs pruned successfully.', [
            'deleted_count' => $deletedCount,
        ]);
    }
}
