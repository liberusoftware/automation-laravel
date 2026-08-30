<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Approvals\Api\Http\Controllers\ApprovalsResourceController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/automation/approvals')->group(function (): void {
    Route::get('/', [ApprovalsResourceController::class, 'index']);
    Route::post('/', [ApprovalsResourceController::class, 'store']);
    Route::get('/{id}', [ApprovalsResourceController::class, 'show']);
    Route::patch('/{id}', [ApprovalsResourceController::class, 'update']);
    Route::delete('/{id}', [ApprovalsResourceController::class, 'destroy']);
    Route::post('/{id}/transition', [ApprovalsResourceController::class, 'transition']);
});
