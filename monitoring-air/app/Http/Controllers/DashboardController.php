<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
       // Ambil data terbaru
       $sensorData = Sensor::orderBy('created_at', 'desc')->take(15)->get();
        
       return view('dashboard', [
           'sensorData' => $sensorData
       ]);
    }

    public function getSensorData()
    {
    // Ambil 10 data sensor terbaru
    $sensorData = Sensor::orderBy('created_at', 'desc')->take(15)->get();

    // Kembalikan dalam bentuk JSON
    return response()->json($sensorData);
    }

    public function checkWaterQuality()
{
    $latestData = Sensor::latest()->first();

    if (!$latestData) {
        return response()->json(['status' => 'ok', 'message' => 'Belum ada data sensor.']);
    }

    $warnings = [];

    if ($latestData->ph < 7.5 || $latestData->ph > 8.5) {
        $warnings[] = "⚠️ pH tidak normal! Saat ini: {$latestData->ph}";
    }
    if ($latestData->suhu < 28 || $latestData->suhu > 32) {
        $warnings[] = "🔥 Suhu ekstrem! Saat ini: {$latestData->suhu}°C";
    }
    if ($latestData->salinitas < 15 || $latestData->salinitas > 30) {
        $warnings[] = "🌊 Salinitas tidak stabil! Saat ini: {$latestData->salinitas} ppm";
    }
    if ($latestData->kekeruhan > 30) {
        $warnings[] = "💧 Kekeruhan tinggi! Saat ini: {$latestData->kekeruhan} NTU";
    }

    if (!empty($warnings)) {
        return response()->json(['status' => 'warning', 'messages' => $warnings]);
    } else {
        return response()->json(['status' => 'ok', 'message' => 'Semua parameter dalam kondisi normal.']);
    }
    }

    
}
