<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\ParcelCropPlanController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('/federations', [FederationController::class, 'getFederations']);
    Route::post('/federations', [FederationController::class, 'store']);
    Route::get('/federations/{federation}', [FederationController::class, 'show']);
    Route::put('/federations/{federation}', [FederationController::class, 'update']);
    Route::delete('/federations/{federation}', [FederationController::class, 'destroy']);
});



 

// API routes for parcel crop plans
Route::get('/crops-by-type/{croptype_id}', [ParcelCropPlanController::class, 'getCropsByType']);
Route::get('/fertilizer-recommendation', [ParcelCropPlanController::class, 'getFertilizerRecommendation']);
Route::get('/crop-configuration/{cropId}', [ParcelCropPlanController::class, 'getCropConfiguration']);
Route::get('/crop-configuration/{cropId}/soil/{soilTypeId}', [ParcelCropPlanController::class, 'getCropConfigurationWithSoil']);
