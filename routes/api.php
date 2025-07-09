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

    Route::get('user/document', [DocumentController::class, 'index']);
    Route::get('user/{user_id}/document', [DocumentController::class, 'getDocumentByUser']);
    Route::post('user/document', [DocumentController::class, 'store']);
    Route::get('user/{user_id}/document/album', [DocumentController::class, 'getAlbum']);
    Route::get('user/{user_id}/document/{year}', [DocumentController::class, 'show']);
    Route::put('user/{user_id}/document/{document_id}/edit', [DocumentController::class, 'update']);
    Route::delete('/user/{user_id}/document/{document_id}', [DocumentController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('marketing/{marketing_id}/document', [MarketingController::class, 'index']);
    Route::post('marketing/document/{document_id}', [MarketingController::class, 'store']);
    Route::patch('marketing/{user_id}/document/{document_id}/edit', [MarketingController::class, 'update']);
    Route::delete('marketing/{user_id}/document/{document_id}', [MarketingController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('koorteknis/{koorteknis_id}/document', [KoorTeknisController::class, 'index']);
    Route::post('koorteknis/document/{document_id}', [KoorTeknisController::class, 'store']);
    Route::patch('koorteknis/{user_id}/document/{document_id}/edit', [KoorTeknisController::class, 'update']);
    Route::delete('koorteknis/{user_id}/document/{document_id}', [KoorTeknisController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('sampling/document', [PenyediaSamplingController::class, 'index']);
    Route::post('sampling/document', [PenyediaSamplingController::class, 'store']);
    Route::patch('sampling/{document_id}/document/edit', [PenyediaSamplingController::class, 'update']);
    Route::delete('sampling/{document_id}/document', [PenyediaSamplingController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('quotation/document', [QuotationController::class, 'index']);
    Route::post('quotation/document', [QuotationController::class, 'store']);
    Route::patch('quotation/{document_id}/document/edit', [QuotationController::class, 'update']);
    Route::delete('quotation/{document_id}/document', [QuotationController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('', [PoTtdQuotationController::class, 'index']);
    Route::get('', [PoTtdQuotationController::class, 'show']);
    Route::post('', [PoTtdQuotationController::class, 'store']);
    Route::patch('', [PoTtdQuotationController::class, 'update']);
    Route::delete('', [PoTtdQuotationController::class, 'destroy']);
});

