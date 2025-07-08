<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\KoorTeknisController;
use App\Http\Controllers\Api\MarketingController;
use App\Http\Controllers\Api\PenyediaSamplingController;
use App\Http\Controllers\Api\PoTtdQuotationController;
use App\Http\Controllers\Api\QuotationController;
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
    Route::get('document/album', [DocumentController::class, 'getAlbum']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('marketing/{marketing_id}/document', [MarketingController::class, 'index']);
    Route::post('marketing/document/{document_id}', [MarketingController::class, 'store']);
    Route::patch('marketing/{user_id}/document/{document_id}/edit', [MarketingController::class, 'update']);
    Route::delete('marketing/{user_id}/document/{document_id}', [MarketingController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('koorteknis/{koorteknis_id}/document', [KoorTeknisController::class, 'index']);
    Route::get('', [KoorTeknisController::class, 'show']);
    Route::post('koorteknis/document/{document_id}', [KoorTeknisController::class, 'store']);
    Route::patch('koorteknis/{user_id}/document/{document_id}/edit', [KoorTeknisController::class, 'update']);
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

