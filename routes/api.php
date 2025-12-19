<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\UploadController;

Route::middleware('auth:sanctum')->group(function () {
    // CSV Import
    Route::post('/import/csv', [ImportController::class, 'store']);

    // Chunked Upload
    Route::post('/upload/start', [UploadController::class, 'start']);
    Route::post('/upload/chunk', [UploadController::class, 'uploadChunk']);
    Route::post('/upload/status', [UploadController::class, 'status']);
    Route::post('/upload/merge', [UploadController::class, 'merge']);
});
