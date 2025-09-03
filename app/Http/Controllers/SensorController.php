<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use Carbon\Carbon;
use App\Helpers\QualityHelper;

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
        $phStatus = QualityHelper::determineParameterStatus('ph', $data->ph);
        $suhuStatus = QualityHelper::determineParameterStatus('suhu', $data->suhu);
        $kekeruhanStatus = QualityHelper::determineParameterStatus('kekeruhan', $data->kekeruhan);
        
        $parameterStatuses = [
            'ph' => $phStatus,
            'suhu' => $suhuStatus,
            'kekeruhan' => $kekeruhanStatus
        ];
        
        $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

        return [
            'created_at' => Carbon::parse($data->created_at)->format('d M Y, H:i'),
            'ph' => $data->ph,
            'suhu' => $data->suhu,
            'kekeruhan' => $data->kekeruhan,
            'kualitas' => $data->kualitas,
            'status_kualitas_air' => $overallStatus['status'],
            'parameter_statuses' => $parameterStatuses
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
                'danger' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['ph'];
                    $q->where('ph', '<', $thresholds['danger_low'])
                      ->orWhere('ph', '>', $thresholds['danger_high']);
                }),
                'warning' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['ph'];
                    $q->whereBetween('ph', [$thresholds['warning_low'], $thresholds['danger_low']])
                      ->orWhereBetween('ph', [$thresholds['danger_high'], $thresholds['warning_high']]);
                }),
                'normal' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['ph'];
                    $q->whereBetween('ph', [$thresholds['normal_low'], $thresholds['normal_high']]);
                }),
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
                'danger' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['suhu'];
                    $q->where('suhu', '<', $thresholds['danger_low'])
                      ->orWhere('suhu', '>', $thresholds['danger_high']);
                }),
                'warning' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['suhu'];
                    $q->whereBetween('suhu', [$thresholds['warning_low'], $thresholds['danger_low']])
                      ->orWhereBetween('suhu', [$thresholds['danger_high'], $thresholds['warning_high']]);
                }),
                'normal' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['suhu'];
                    $q->whereBetween('suhu', [$thresholds['normal_low'], $thresholds['normal_high']]);
                }),
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
                'danger' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['kekeruhan'];
                    $q->where('kekeruhan', '>', $thresholds['danger_low']);
                }),
                'warning' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['kekeruhan'];
                    $q->whereBetween('kekeruhan', [$thresholds['warning_low'], $thresholds['danger_low']]);
                }),
                'normal' => $query->where(function($q) {
                    $thresholds = QualityHelper::getThresholds()['kekeruhan'];
                    $q->whereBetween('kekeruhan', [$thresholds['normal_low'], $thresholds['normal_high']]);
                }),
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
