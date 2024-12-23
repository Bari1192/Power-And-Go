<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarStatusController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RenthistoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('bills/filter/{type}', [BillController::class, 'filter']);
Route::get('cars/{car}/bills', [CarController::class, 'filterCarFines']);
Route::get('renthistories/filterCarHistory/{type}/{car}', [RenthistoryController::class, 'filterCarHistory']);

Route::apiResource('/tickets', TicketController::class);

Route::apiResource('renthistories', RenthistoryController::class); ## -> 4 + 1 API!

Route::apiResource('/bills', BillController::class);

Route::apiResource('fleets', FleetController::class);

Route::apiResource('cars', CarController::class);
Route::apiResource('carstatus', CarStatusController::class);

Route::apiResource('persons', PersonController::class);
Route::apiResource('users',UserController::class);
