<?php

use App\Http\Controllers\AutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('autok', AutoController::class)->only(['index', 'show', 'store', 'destroy']);
// Route::apiResources('autok', AutoController::class)->only(['index', 'show', 'store', 'destroy']);
