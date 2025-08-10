<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\CareTaskController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // v1 API 路由群組
    Route::prefix('v1')->group(function () {
        Route::apiResource('pets', PetController::class);
        Route::apiResource('tasks', CareTaskController::class);
    });
});
