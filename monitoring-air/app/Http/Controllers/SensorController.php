<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function history()
    {
        return view('history');
    }

    public function getHistoryData()
    {
    $sensorData = Sensor::orderBy('created_at', 'desc')->get(); 

    $sensorData = $sensorData->map(function ($data) {
        return [
            'created_at' => Carbon::parse($data->created_at)->format('d M Y, H:i'), // Format tanggal
            'ph' => $data->ph,
            'suhu' => $data->suhu,
            'salinitas' => $data->salinitas,
            'kekeruhan' => $data->kekeruhan
        ];
    });

    return response()->json($sensorData);
    }

    public function filterHistory(Request $request)
{
    $query = Sensor::query();

    // Filter berdasarkan rentang tanggal jika tersedia
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('created_at', [
            \Carbon\Carbon::parse($request->start_date)->startOfDay(),
            \Carbon\Carbon::parse($request->end_date)->endOfDay(),
        ]);
    }

    if ($request->start_date) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    // Filter berdasarkan pH
    if ($request->ph && $request->ph != 'all') {
        if ($request->ph == 'acid') {
            $query->where('ph', '<', 7.5);
        } elseif ($request->ph == 'neutral') {
            $query->whereBetween('ph', [7.5, 8.5]);
        } elseif ($request->ph == 'base') {
            $query->where('ph', '>', 8.5);
        }
    }

    // Filter berdasarkan suhu
    if ($request->suhu && $request->suhu != 'all') {
        if ($request->suhu == 'cold') {
            $query->where('suhu', '<', 28);
        } elseif ($request->suhu == 'optimal_suhu') {
            $query->whereBetween('suhu', [28, 32]);
        } elseif ($request->suhu == 'hot') {
            $query->where('suhu', '>', 32);
        }
    }

    // Filter berdasarkan salinitas
    if ($request->salinitas && $request->salinitas != 'all') {
        if ($request->salinitas == 'low') {
            $query->where('salinitas', '<', 15);
        } elseif ($request->salinitas == 'optimal_salinitas') {
            $query->whereBetween('salinitas', [15, 30]);
        } elseif ($request->salinitas == 'high') {
            $query->where('salinitas', '>', 30);
        }
    }

    // Filter berdasarkan kekeruhan
    if ($request->kekeruhan && $request->kekeruhan != 'all') {
        if ($request->kekeruhan == 'clear') {
            $query->where('kekeruhan', '<', 15);
        } elseif ($request->kekeruhan == 'optimal_kekeruhan') {
            $query->whereBetween('kekeruhan', [15, 30]);
        } elseif ($request->kekeruhan == 'turbid') {
            $query->where('kekeruhan', '>', 30);
        }
    }

    // Ambil data setelah filter
    $sensorData = $query->orderBy('created_at', 'desc')->get();

    // Format tanggal sebelum dikirim ke frontend
    $sensorData->transform(function ($data) {
        $data->created_at = \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i');
        return $data;
    });

    // Kirim data dalam format JSON
    return response()->json($sensorData);
}



}

