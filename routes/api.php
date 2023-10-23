<?php

use App\Http\Controllers\API\AnalyticController;
use App\Http\Controllers\API\CandidateController;
use App\Http\Controllers\API\VoteController;
use Illuminate\Support\Facades\Route;

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

Route::controller(AnalyticController::class)->prefix('analytic')->group(function () {
    Route::get('/', 'index');
});

Route::controller(CandidateController::class)->prefix('candidates')->group(function () {
    Route::get('/', 'index');
});

Route::controller(VoteController::class)->prefix('votes')->group(function () {
    Route::post('/', 'store');
});