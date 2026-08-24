<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Liberu\PlatformOrchestration\Api\Http\Controllers\CompositionController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/liberu/platform')->group(function (): void {
    Route::get('/compositions', [CompositionController::class, 'index']);
    Route::post('/compositions', [CompositionController::class, 'store']);
    Route::get('/compositions/{id}', [CompositionController::class, 'show']);
});
