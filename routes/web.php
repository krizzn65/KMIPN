<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
// Route Dahsboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/sensor-data', [DashboardController::class, 'getSensorData']);
Route::get('/api/chart-data', [DashboardController::class, 'getChartData']);
Route::get('/dashboard/check-water', [DashboardController::class, 'checkWaterQuality']);

// route history

// Route halaman riwayat
Route::get('/history', [SensorController::class, 'history'])->name('history');
// Route ambil semua data
Route::get('/history/data', [SensorController::class, 'getHistoryData'])->name('history.data');
// Route filter data
Route::get('/history/filter', [SensorController::class, 'filterHistory'])->name('history.filter');

Route::get('/update-kualitas-lama', [DashboardController::class, 'updateMissingQuality']);
