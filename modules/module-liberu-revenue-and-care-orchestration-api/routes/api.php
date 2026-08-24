<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Api\Http\Controllers\HealthSignalController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/liberu/care')->group(function (): void {
    Route::get('/signals', [HealthSignalController::class, 'index']);
    Route::post('/signals', [HealthSignalController::class, 'store']);
});
