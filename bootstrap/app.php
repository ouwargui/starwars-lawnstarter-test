<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\MetricsMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(
            append: [
                HandleInertiaRequests::class,
                AddLinkHeadersForPreloadedAssets::class,
            ],
            prepend: [
                MetricsMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle SWAPI 404 responses - convert to proper 404 page
        $exceptions->render(function (RequestException $e, Request $request) {
            $statusCode = $e->response->status();
            $url = (string) $e->response->effectiveUri();

            if ($statusCode === 404) {
                Log::warning('SWAPI resource not found', [
                    'url' => $url,
                    'status' => $statusCode,
                ]);

                throw new NotFoundHttpException('The requested resource was not found.');
            }

            // Log other HTTP errors from SWAPI
            Log::error('SWAPI request failed', [
                'url' => $url,
                'status' => $statusCode,
                'message' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'External service error',
                    'message' => 'Unable to fetch data from the Star Wars API.',
                ], 503);
            }

            return Inertia::render('error', [
                'status' => 503,
                'message' => 'Unable to fetch data from the Star Wars API. Please try again later.',
            ])->toResponse($request)->setStatusCode(503);
        });

        // Handle SWAPI connection failures
        $exceptions->render(function (ConnectionException $e, Request $request) {
            Log::error('SWAPI connection failed', [
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Connection error',
                    'message' => 'Unable to connect to the Star Wars API.',
                ], 503);
            }

            return Inertia::render('error', [
                'status' => 503,
                'message' => 'Unable to connect to the Star Wars API. Please try again later.',
            ])->toResponse($request)->setStatusCode(503);
        });
    })->create();
