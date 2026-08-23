<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\AiGateway\Api\Http\Controllers\AiGatewayResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/ai-gateway')->group(function (): void {
    Route::get('/', [AiGatewayResourceController::class, 'index']);
    Route::post('/', [AiGatewayResourceController::class, 'store']);
});
