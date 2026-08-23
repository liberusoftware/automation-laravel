<?php

use Illuminate\Support\Facades\Route;
use Liberu\Modules\Automation\Image\Api\Http\Controllers\ImageResourceController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/v1/automation/image')->group(function (): void {
    Route::get('/', [ImageResourceController::class, 'index']);
    Route::post('/', [ImageResourceController::class, 'store']);
});
