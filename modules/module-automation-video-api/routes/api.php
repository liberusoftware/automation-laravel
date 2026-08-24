<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Video\Api\Http\Controllers\VideoResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/video')->group(function (): void {
    Route::get('/', [VideoResourceController::class, 'index']);
    Route::post('/', [VideoResourceController::class, 'store']);
    Route::get('/{id}', [VideoResourceController::class, 'show']);
    Route::patch('/{id}', [VideoResourceController::class, 'update']);
    Route::delete('/{id}', [VideoResourceController::class, 'destroy']);
});
