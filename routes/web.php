<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
// Controller imports
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
// ---------------------------
// Dashboard Routes
// ---------------------------
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// API internal untuk dashboard (real-time & chart)
Route::get('/api/sensor-data', [DashboardController::class, 'getSensorData']);
Route::get('/api/chart-data', [DashboardController::class, 'getChartData']);
Route::get('/dashboard/check-water', [DashboardController::class, 'checkWaterQuality']);
Route::get('/update-kualitas-lama', [DashboardController::class, 'updateMissingQuality']);

// ---------------------------
// History Routes
// ---------------------------

// Halaman utama History (menampilkan tampilan history.blade.php)
Route::get('/history', function () {
    return view('history');
})->name('history');

// Endpoint untuk mengambil data history (dipanggil oleh JS di history.blade.php)
Route::get('/history/data', [HistoryController::class, 'getHistoryData'])->name('history.data');
