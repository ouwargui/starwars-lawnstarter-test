<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use App\Services\RequestContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware that logs request metrics for analytics.
 *
 * Captures timing, endpoint, cache hits, and resource information
 * for each request and stores it in the request_logs table.
 */
final class MetricsMiddleware
{
    public function __construct(
        protected RequestContext $context,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $startTime = hrtime(true);

        /** @var Response $response */
        $response = $next($request);

        $this->logRequest($request, $response, $startTime);

        return $response;
    }

    protected function logRequest(Request $request, Response $response, int $startTime): void
    {
        $durationNs = hrtime(true) - $startTime;
        $durationMs = (int) ($durationNs / 1_000_000);

        $endpoint = $this->resolveEndpoint($request);
        $statusCode = $response->getStatusCode();

        RequestLog::create([
            'endpoint' => $endpoint,
            'method' => $request->method(),
            'query' => $this->extractSearchQuery($request),
            'search_type' => $request->input('type'),
            'resource_id' => $this->context->resourceId,
            'resource_name' => $this->context->resourceName,
            'referer' => $this->sanitizeReferer($request->header('referer')),
            'duration_ms' => $durationMs,
            'status_code' => $statusCode,
            'swapi_cache_hits' => $this->context->swapiCacheHits,
            'swapi_cache_misses' => $this->context->swapiCacheMisses,
            'is_error' => $statusCode >= 400,
            'created_at' => now(),
        ]);
    }

    /**
     * Resolve the endpoint that was called using the request.
     */
    protected function resolveEndpoint(Request $request): string
    {
        $route = $request->route();

        if ($route === null) {
            return $request->path();
        }

        $name = $route->getName();

        if ($name !== null) {
            return $name;
        }

        return $request->path();
    }

    /**
     * Extract the search query parameter if present.
     */
    protected function extractSearchQuery(Request $request): ?string
    {
        $query = $request->input('q');

        if (! is_string($query) || $query === '') {
            return null;
        }

        return mb_substr($query, 0, 255);
    }

    /**
     * Sanitize and truncate the referer header.
     */
    protected function sanitizeReferer(?string $referer): ?string
    {
        if ($referer === null || $referer === '') {
            return null;
        }

        return mb_substr($referer, 0, 255);
    }
}
