<?php

use App\Http\Controllers\API\ApiSensorController;
use Illuminate\Support\Facades\Route;

Route::post('/sensor', [ApiSensorController::class, 'store']);
Route::get('/sensor-data', [SensorController::class, 'getSensorData']);
Route::get('/chart-data', [SensorController::class, 'getChartData']);
