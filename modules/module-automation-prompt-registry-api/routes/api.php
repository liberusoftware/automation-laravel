<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\PromptRegistry\Api\Http\Controllers\PromptRegistryResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/prompt-registry')->group(function (): void {
    Route::get('/', [PromptRegistryResourceController::class, 'index']);
    Route::post('/', [PromptRegistryResourceController::class, 'store']);
    Route::get('/{id}', [PromptRegistryResourceController::class, 'show']);
    Route::patch('/{id}', [PromptRegistryResourceController::class, 'update']);
    Route::delete('/{id}', [PromptRegistryResourceController::class, 'destroy']);
});
