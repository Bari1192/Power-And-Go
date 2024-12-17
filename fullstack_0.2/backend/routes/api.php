<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\CarStatusController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\RenthistoryController;
use App\Http\Controllers\SzamlaController;
use App\Http\Controllers\SzemelyController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('szamlak/filter/{type}', [SzamlaController::class, 'filter']);
Route::get('cars/{car}/szamlak', [AutoController::class, 'filterCarFines']);
Route::get('renthistories/filterCarHistory/{type}/{car}', [RenthistoryController::class, 'filterCarHistory']);

Route::apiResource('/tickets', TicketController::class);
Route::apiResource('/renthistories', RenthistoryController::class);
Route::apiResource('szamlak', SzamlaController::class);

Route::apiResource('fleets', FleetController::class);

Route::apiResource('cars', AutoController::class);
Route::apiResource('carstatus', CarStatusController::class);

Route::apiResource('szemelyek', SzemelyController::class);
Route::apiResource('users',UserController::class);
