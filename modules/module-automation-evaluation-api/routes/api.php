<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Evaluation\Api\Http\Controllers\EvaluationResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/evaluation')->group(function (): void {
    Route::get('/', [EvaluationResourceController::class, 'index']);
    Route::post('/', [EvaluationResourceController::class, 'store']);
});
