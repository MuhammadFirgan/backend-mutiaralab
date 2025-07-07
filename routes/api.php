<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\KoorTeknisController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\PenyediaSamplingController;
use App\Http\Controllers\PoTtdQuotationController;
use App\Http\Controllers\QuotationController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('marketing/{marketing_id}/document', [MarketingController::class, 'index']);
    Route::get('', [MarketingController::class, 'show']);
    Route::post('marketing/document', [MarketingController::class, 'store']);
    Route::patch('', [MarketingController::class, 'update']);
    Route::delete('', [MarketingController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('', [KoorTeknisController::class, 'index']);
    Route::get('', [KoorTeknisController::class, 'show']);
    Route::post('', [KoorTeknisController::class, 'store']);
    Route::patch('', [KoorTeknisController::class, 'update']);
    Route::delete('', [KoorTeknisController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('', [PenyediaSamplingController::class, 'index']);
    Route::get('', [PenyediaSamplingController::class, 'show']);
    Route::post('', [PenyediaSamplingController::class, 'store']);
    Route::patch('', [PenyediaSamplingController::class, 'update']);
    Route::delete('', [PenyediaSamplingController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('', [QuotationController::class, 'index']);
    Route::get('', [QuotationController::class, 'show']);
    Route::post('', [QuotationController::class, 'store']);
    Route::patch('', [QuotationController::class, 'update']);
    Route::delete('', [QuotationController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('', [PoTtdQuotationController::class, 'index']);
    Route::get('', [PoTtdQuotationController::class, 'show']);
    Route::post('', [PoTtdQuotationController::class, 'store']);
    Route::patch('', [PoTtdQuotationController::class, 'update']);
    Route::delete('', [PoTtdQuotationController::class, 'destroy']);
});

