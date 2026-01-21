<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Statistics Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration values for statistics computation and request log
    | retention. These values control how long logs are kept and how
    | many items are shown in top lists.
    |
    */

    /**
     * Number of days to retain request logs.
     * Logs older than this will be pruned by PruneRequestLogsJob.
     */
    'retention_days' => (int) env('STATISTICS_RETENTION_DAYS', 30),

    /**
     * Maximum number of items to show in "top" lists (queries, movies, etc.)
     */
    'top_limit' => (int) env('STATISTICS_TOP_LIMIT', 5),

    /**
     * Chunk size for batch deleting old records.
     * Larger values are faster but use more memory.
     */
    'prune_chunk_size' => (int) env('STATISTICS_PRUNE_CHUNK_SIZE', 1000),

];
