<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Liberu\ExecutiveInsights\Api\Http\Controllers\MetricController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/liberu/insights')->group(function (): void {
    Route::get('/metrics', [MetricController::class, 'index']);
    Route::post('/metrics', [MetricController::class, 'store']);
});
