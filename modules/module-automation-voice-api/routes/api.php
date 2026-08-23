<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Voice\Api\Http\Controllers\VoiceResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/voice')->group(function (): void {
    Route::get('/', [VoiceResourceController::class, 'index']);
    Route::post('/', [VoiceResourceController::class, 'store']);
});
