<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\AutomationCore\Api\Http\Controllers\AutomationCoreResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/automation-core')->group(function (): void {
    Route::get('/', [AutomationCoreResourceController::class, 'index']);
    Route::post('/', [AutomationCoreResourceController::class, 'store']);
});
