<?php

use App\Http\Controllers\MoviesController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticsController;
use App\Http\Middleware\MetricsMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', SearchController::class)->name('search');

Route::get('/people/{id}', PeopleController::class)->name('people');
Route::get('/movies/{id}', MoviesController::class)->name('movies');

Route::get('/api/statistics', StatisticsController::class)
    ->name('statistics')
    ->withoutMiddleware(MetricsMiddleware::class);
