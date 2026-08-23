<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Connectors\Api\Http\Controllers\ConnectorsResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/connectors')->group(function (): void {
    Route::get('/', [ConnectorsResourceController::class, 'index']);
    Route::post('/', [ConnectorsResourceController::class, 'store']);
});
