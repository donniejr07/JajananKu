<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('transactions', TransactionController::class)->names('api.transactions');
    Route::apiResource('categories', CategoryController::class)->names('api.categories');

    Route::get('reports/summary', [ReportController::class, 'summary']);
    Route::get('reports/breakdown', [ReportController::class, 'breakdown']);
});
