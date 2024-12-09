<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\SzamlaController;
use App\Http\Controllers\SzemelyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('szamlak/filter/', [SzamlaController::class, 'filter']);
Route::apiResource('szamlak', SzamlaController::class)->except(['update']);

Route::apiResource('autok', AutoController::class)->except(['update']);
Route::apiResource('szemelyek', SzemelyController::class)->except(['update']);
Route::apiResource('fleets', FleetController::class)->except(['update']);
