<?php

use App\Http\Controllers\MoviesController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'index'])->name('search');

Route::get('/people/{id}', PeopleController::class)->name('people');
Route::get('/movies/{id}', MoviesController::class)->name('movies');
