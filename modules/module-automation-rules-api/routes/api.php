<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Rules\Api\Http\Controllers\RulesResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/rules')->group(function (): void {
    Route::get('/', [RulesResourceController::class, 'index']);
    Route::post('/', [RulesResourceController::class, 'store']);
    Route::get('/{id}', [RulesResourceController::class, 'show']);
    Route::patch('/{id}', [RulesResourceController::class, 'update']);
    Route::delete('/{id}', [RulesResourceController::class, 'destroy']);
});
