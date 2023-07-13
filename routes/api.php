<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group( function () {
    Route::get('getMovies', [MovieController::class, 'getMovies']);
    Route::get('getMoviesPage', [MovieController::class, 'getMoviesPage']);
    Route::get('getSingleMovie/{id}', [MovieController::class, 'getSingleMovie']);
    Route::post('saveMovie', [MovieController::class, 'saveMovie']);
    Route::post('updateMovie/{id}', [MovieController::class, 'updateMovie']);
    Route::post('deleteMovie/{id}', [MovieController::class, 'deleteMovie']);
});

