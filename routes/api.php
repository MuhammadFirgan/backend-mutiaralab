<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

route::middleware('auth:sanctum')->group(function() {

    Route::get('user/{user_id}/document', [DocumentController::class, 'index']);
    Route::post('user/document', [DocumentController::class, 'store']);
    Route::get('user/{user_id}/document/{year}', [DocumentController::class, 'show']);
    Route::put('user/{user_id}/document/{document_id}/edit', [DocumentController::class, 'update']);
    Route::delete('/user/{user_id}/document/{document_id}', [DocumentController::class, 'destroy']);
});

