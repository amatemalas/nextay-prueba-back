<?php

use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/room-types/summary', [RoomTypeController::class, 'summary']);
Route::get('/room-types/latest-rates', [RoomTypeController::class, 'latestRates']);
