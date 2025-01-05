<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarStatusController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\GoogleMapsController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('bills/filter/{type}', [BillController::class, 'filter']);
Route::get('cars/{car}/bills', [CarController::class, 'filterCarFines']);

Route::get('cars/{car}/tickets', [CarController::class, 'carTickets']);
Route::get('cars/{car}/renthistory', [CarController::class, 'carWithRentHistory']);
Route::get('/bills/{car}/fees',[BillController::class,'carFees']);

Route::get('/googlemapsapi', [GoogleMapsController::class, 'getApiUrl']);   ## Ez az input alapján a címet adja meg
Route::get('/geocode', [GoogleMapsController::class, 'getGeocode']);        ## Ez a térképet inicializálja és jeleníti meg rajta.


Route::apiResource('/tickets', TicketController::class);

Route::apiResource('/bills', BillController::class);

Route::apiResource('fleets', FleetController::class);

Route::apiResource('cars', CarController::class);
Route::apiResource('carstatus', CarStatusController::class);

Route::apiResource('persons', PersonController::class);
Route::apiResource('users',UserController::class);
Route::apiResource('employees',EmployeeController::class);
