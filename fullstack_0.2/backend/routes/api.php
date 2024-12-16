<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\CarStatusController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\RenthistoryController;
use App\Http\Controllers\SzamlaController;
use App\Http\Controllers\SzemelyController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('szamlak/filter/{type}', [SzamlaController::class, 'filter']);
Route::get('cars/{car}/szamlak', [AutoController::class, 'filterCarFines']);

Route::apiResource('/tickets', TicketController::class);
Route::apiResource('/renthistories', RenthistoryController::class);
Route::apiResource('szamlak', SzamlaController::class)->except(['update']);

Route::apiResource('carstatus', CarStatusController::class)->except(['update']);

Route::apiResource('cars', AutoController::class)->except(['update']);
Route::apiResource('szemelyek', SzemelyController::class)->except(['update']);

Route::apiResource('fleets', FleetController::class)->only(['show', 'index', 'store', 'destroy']);
