<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarStatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\GoogleMapsController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/register", [RegisterController::class, "store"])->name("register.store");
Route::post("/authenticate", [AuthController::class, "authenticate"])->name("auth.authenticate");


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('cars/{car}/fees', [CarController::class, 'filterCarFees']);             # 1 
Route::get('cars/{car}/description', [CarController::class, 'carLastTicketDescription']);
Route::get('cars/{car}/tickets', [CarController::class, 'carTickets']);
Route::get('cars/{car}/renthistory', [CarController::class, 'carWithRentHistory']); # Csak 1 autó history


Route::get('/bills/closedrentsbills', [BillController::class, 'closedRents']);          # Lezárt státuszú autók.
Route::get('/bills/fees', [BillController::class, 'feesCollection']);          # Lezárt státuszú autók.
Route::get('/googlemapsapi', [GoogleMapsController::class, 'getApiUrl']);   ## Ez az input alapján a címet adja meg
Route::get('/geocode', [GoogleMapsController::class, 'getGeocode']);        ## Ez a térképet inicializálja és jeleníti meg rajta.

Route::apiResource('cars', CarController::class);
Route::apiResource('carstatus', CarStatusController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('fleets', FleetController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('tickets', TicketController::class);

Route::apiResource('persons', PersonController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('bills', BillController::class);

# Dinamikusság kell, hogy ha létrejön a dolgozó, akkor 
# dolgozói kedvezményben legyen (pl: 50% fix).

