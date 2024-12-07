<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\SzamlaController;
use App\Http\Controllers\SzemelyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('autok', AutoController::class)->only(['index', 'show', 'store', 'destroy']);
Route::apiResource('szemelyek', SzemelyController::class)->only(['index', 'store']);

Route::apiResource('szamlak', SzamlaController::class)->only(['index', 'show', 'store', 'destroy']);
Route::get('szamlak/filter/{type}',[SzamlaController::class, 'filter']);