<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Connectors\Api\Http\Controllers\ConnectorsResourceController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/automation/connectors')->group(function (): void {
    Route::get('/', [ConnectorsResourceController::class, 'index']);
    Route::post('/', [ConnectorsResourceController::class, 'store']);
    Route::get('/{id}', [ConnectorsResourceController::class, 'show']);
    Route::patch('/{id}', [ConnectorsResourceController::class, 'update']);
    Route::delete('/{id}', [ConnectorsResourceController::class, 'destroy']);
});
