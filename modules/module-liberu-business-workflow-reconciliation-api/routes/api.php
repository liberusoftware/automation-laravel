<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Api\Http\Controllers\WorkflowRunController;

Route::middleware(['api', 'auth:sanctum', 'throttle:60,1'])->prefix('api/v1/liberu/reconciliation')->group(function (): void {
    Route::get('/runs', [WorkflowRunController::class, 'index']);
    Route::post('/runs', [WorkflowRunController::class, 'store']);
});
