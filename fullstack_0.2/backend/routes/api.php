<?php

use App\Http\Controllers\API\AutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/cars', [AutoController::class, 'index']);
Route::delete('/cars/{id}', [AutoController::class, 'destroy']);
