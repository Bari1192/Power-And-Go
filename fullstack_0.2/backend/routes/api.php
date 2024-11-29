<?php

use App\Http\Controllers\API\AutoController;
use App\Http\Controllers\API\LezartBerlesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

### Autok API -k | Full lista | Törlés a listából
Route::get('/cars', [AutoController::class, 'index']);
Route::delete('/cars/{id}', [AutoController::class, 'destroy'])
->whereNumber('id');

### Lezárt Bérlések API -k | Full lista | Törlés a listából
Route::get('/renthistories',[LezartBerlesController::class, 'index']);
Route::get('/renthistories/{id}',[LezartBerlesController::class, 'destroy'])->whereNumber('id');
