<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Voice\Api\Http\Controllers\VoiceResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/voice')->group(function (): void {
    Route::get('/', [VoiceResourceController::class, 'index']);
    Route::post('/', [VoiceResourceController::class, 'store']);
    Route::get('/{id}', [VoiceResourceController::class, 'show']);
    Route::patch('/{id}', [VoiceResourceController::class, 'update']);
    Route::delete('/{id}', [VoiceResourceController::class, 'destroy']);
});
