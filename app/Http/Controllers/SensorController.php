<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use Carbon\Carbon;

class SensorController extends Controller
{
    /**
     * Display the history view.
     */
    public function history()
    {
        return view('history');
    }

    /**
     * Get all sensor history data.
     */
    public function getHistoryData()
    {
        $sensorData = Sensor::orderByDesc('created_at')->get();

        $formattedData = $sensorData->map(fn($data) => $this->formatSensorData($data));

        return response()->json($formattedData);
    }

    /**
     * Filter sensor history data based on request.
     */
    public function filterHistory(Request $request)
    {
        $query = Sensor::query();

        $this->applyDateFilter($query, $request);
        $this->applyPhFilter($query, $request);
        $this->applySuhuFilter($query, $request);
        $this->applyKekeruhanFilter($query, $request);

        $sensorData = $query->orderByDesc('created_at')->get();

        $formattedData = $sensorData->map(fn($data) => $this->formatSensorData($data));

        return response()->json($formattedData);
    }

    /**
     * Format a single sensor data record.
     */
    private function formatSensorData($data)
    {
        return [
            'created_at' => Carbon::parse($data->created_at)->format('d M Y, H:i'),
            'ph' => $data->ph,
            'suhu' => $data->suhu,
            'kekeruhan' => $data->kekeruhan,
            'kualitas' => $data->kualitas,
            'status_kualitas_air' => $this->determineWaterQualityStatus($data->kualitas)
        ];
    }

    /**
     * Apply date range filters to the query.
     */
    private function applyDateFilter($query, Request $request)
    {
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    }

    /**
     * Apply pH filters to the query.
     */
    private function applyPhFilter($query, Request $request)
    {
        if ($request->filled('ph') && $request->ph !== 'all') {
            match ($request->ph) {
                'acid' => $query->where('ph', '<', 7.5),
                'neutral' => $query->whereBetween('ph', [7.5, 8.5]),
                'base' => $query->where('ph', '>', 8.5),
                default => null
            };
        }
    }

    /**
     * Apply suhu (temperature) filters to the query.
     */
    private function applySuhuFilter($query, Request $request)
    {
        if ($request->filled('suhu') && $request->suhu !== 'all') {
            match ($request->suhu) {
                'cold' => $query->where('suhu', '<', 28),
                'optimal_suhu' => $query->whereBetween('suhu', [28, 32]),
                'hot' => $query->where('suhu', '>', 32),
                default => null
            };
        }
    }

    /**
     * Apply kekeruhan (turbidity) filters to the query.
     */
    private function applyKekeruhanFilter($query, Request $request)
    {
        if ($request->filled('kekeruhan') && $request->kekeruhan !== 'all') {
            match ($request->kekeruhan) {
                'clear' => $query->where('kekeruhan', '<', 15),
                'optimal_kekeruhan' => $query->whereBetween('kekeruhan', [15, 30]),
                'turbid' => $query->where('kekeruhan', '>', 30),
                default => null
            };
        }
    }

     private function determineWaterQualityStatus($value)
    {
        if ($value <= 40) {
            return 'Buruk';
        } elseif ($value >= 60) {
            return 'Baik';
        } else {
            return 'Sedang';
        }
    }

    

}
