<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $endpoint
 * @property string $method
 * @property string|null $query
 * @property string|null $search_type
 * @property int|null $resource_id
 * @property string|null $resource_name
 * @property string|null $referer
 * @property int $duration_ms
 * @property int $status_code
 * @property int $swapi_cache_hits
 * @property int $swapi_cache_misses
 * @property bool $is_error
 * @property \Carbon\CarbonImmutable $created_at
 */
final class RequestLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'endpoint',
        'method',
        'query',
        'search_type',
        'resource_id',
        'resource_name',
        'referer',
        'duration_ms',
        'status_code',
        'swapi_cache_hits',
        'swapi_cache_misses',
        'is_error',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'resource_id' => 'integer',
            'duration_ms' => 'integer',
            'status_code' => 'integer',
            'swapi_cache_hits' => 'integer',
            'swapi_cache_misses' => 'integer',
            'is_error' => 'boolean',
            'created_at' => 'immutable_datetime',
        ];
    }

    /**
     * Scope to filter logs within a given number of days.
     */
    public function scopeWithinDays(Builder $query, int $days): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to filter logs for a specific endpoint.
     */
    public function scopeForEndpoint(Builder $query, string $endpoint): Builder
    {
        return $query->where('endpoint', $endpoint);
    }

    /**
     * Scope to filter logs with errors.
     */
    public function scopeWithErrors(Builder $query): Builder
    {
        return $query->where('is_error', true);
    }

    /**
     * Scope to filter logs with search queries.
     */
    public function scopeWithQuery(Builder $query): Builder
    {
        return $query->whereNotNull('query');
    }

    /**
     * Scope to filter logs for movie resources.
     */
    public function scopeMovies(Builder $query): Builder
    {
        return $query->where('endpoint', 'movies');
    }

    /**
     * Scope to filter logs for people resources.
     */
    public function scopePeople(Builder $query): Builder
    {
        return $query->where('endpoint', 'people');
    }
}
