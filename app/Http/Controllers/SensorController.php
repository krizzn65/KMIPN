<?php

namespace App\Http\Controllers;

use App\Helpers\FuzzyHelper;
use App\Models\Sensor;

class SensorController extends Controller
{
    // API: Ambil data sensor rata-rata per 1 menit (24 jam terakhir)
    public function getSensorData()
    {
        $data = Sensor::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as minute,
                AVG(ph) as ph,
                AVG(suhu) as suhu,
                AVG(kekeruhan) as kekeruhan
            ')
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('minute')
            ->orderBy('minute', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($item) {
                // konversi kekeruhan ke persen
                $item->kekeruhan = min(($item->kekeruhan / 100) * 100, 100);
                $item->kualitas = FuzzyHelper::hitungKualitasAir($item->ph, $item->suhu, $item->kekeruhan);

                return $item;
            });

        return response()->json($data);
    }

    // API: Data untuk grafik
    public function getChartData()
    {
        $data = Sensor::selectRaw('
                DATE_FORMAT(created_at, "%H:%i") as minute,
                AVG(ph) as ph,
                AVG(suhu) as suhu,
                AVG(kekeruhan) as kekeruhan
            ')
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('minute')
            ->orderBy('minute', 'asc')
            ->get();

        $labels = $data->pluck('minute');
        $datasets = [
            'ph' => $data->pluck('ph'),
            'suhu' => $data->pluck('suhu'),
            'kekeruhan' => $data->pluck('kekeruhan')->map(fn ($v) => min(($v / 100) * 100, 100)),
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets,
        ]);
    }
}
