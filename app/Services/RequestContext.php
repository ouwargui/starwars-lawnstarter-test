<?php

namespace App\Services;

/**
 * Request-scoped container for metrics data.
 *
 * This service is registered as a scoped singleton, meaning each HTTP request
 * gets its own instance. It allows different layers to share request-specific data
 * without tight coupling.
 */
final class RequestContext
{
    /**
     * Number of SWAPI cache hits during this request.
     */
    public int $swapiCacheHits = 0;

    /**
     * Number of SWAPI cache misses during this request.
     */
    public int $swapiCacheMisses = 0;

    /**
     * The ID of the resource being accessed (movie or person).
     * Set by MoviesService or PeopleService.
     */
    public ?int $resourceId = null;

    /**
     * The name/title of the resource being accessed.
     * Set by MoviesService (movie title) or PeopleService (person name).
     */
    public ?string $resourceName = null;

    /**
     * The type of resource: 'movie' or 'person'.
     * Set by the respective service.
     */
    public ?string $resourceType = null;

    /**
     * Record a SWAPI cache hit.
     */
    public function recordCacheHit(): void
    {
        $this->swapiCacheHits++;
    }

    /**
     * Record a SWAPI cache miss.
     */
    public function recordCacheMiss(): void
    {
        $this->swapiCacheMisses++;
    }

    /**
     * Reset the context to its initial state.
     */
    public function reset(): void
    {
        $this->swapiCacheHits = 0;
        $this->swapiCacheMisses = 0;
        $this->resourceId = null;
        $this->resourceName = null;
        $this->resourceType = null;
    }
}
