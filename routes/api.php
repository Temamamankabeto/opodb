<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FederationController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/federations', [FederationController::class, 'getFederations']);
    Route::post('/federations', [FederationController::class, 'store']);
    Route::get('/federations/{federation}', [FederationController::class, 'show']);
    Route::put('/federations/{federation}', [FederationController::class, 'update']);
    Route::delete('/federations/{federation}', [FederationController::class, 'destroy']);
});
