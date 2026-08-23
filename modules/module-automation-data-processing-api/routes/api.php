<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\DataProcessing\Api\Http\Controllers\DataProcessingResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/data-processing')->group(function (): void {
    Route::get('/', [DataProcessingResourceController::class, 'index']);
    Route::post('/', [DataProcessingResourceController::class, 'store']);
});
