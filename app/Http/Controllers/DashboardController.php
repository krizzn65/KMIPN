<?php

namespace App\Http\Controllers;

use App\Helpers\QualityHelper;
use App\Models\Sensor;

class DashboardController extends Controller
{
    public function index()
    {
        $sensorData = Sensor::orderBy('created_at', 'desc')->take(15)->get();

        return view('dashboard', ['sensorData' => $sensorData]);
    }

    public function getSensorData()
    {
        $sensorData = Sensor::orderBy('created_at', 'desc')->take(15)->get();

        if ($sensorData->isEmpty()) {
            return response()->json([]);
        }

        $latest = $sensorData->first();

        // Calculate quality for the latest data
        $quality = round($this->calculateFuzzyQuality($latest->ph, $latest->suhu, $latest->kekeruhan), 2);

        // Get parameter statuses and overall status
        $parameterStatuses = QualityHelper::getAllParameterStatuses($latest->ph, $latest->suhu, $latest->kekeruhan);
        $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

        // Format the latest data with all required fields
        $formattedLatest = [
            'id' => $latest->id,
            'ph' => $latest->ph,
            'suhu' => $latest->suhu,
            'kekeruhan' => $latest->kekeruhan,
            'kualitas' => $quality,
            'quality' => $quality,
            'created_at' => $latest->created_at,
            'updated_at' => $latest->updated_at,
            'parameter_statuses' => $parameterStatuses,
            'overall_status' => $overallStatus,
        ];

        return response()->json([$formattedLatest]);
    }

    /**
     * Get chart data for trend visualization
     */
    public function getChartData()
    {
        // Get data from last 24 hours for the chart
        $sensorData = Sensor::where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'asc')
            ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                'ph' => [],
                'suhu' => [],
                'kekeruhan' => [],
            ],
        ];

        foreach ($sensorData as $data) {
            $chartData['labels'][] = $data->created_at->format('H:i');
            $chartData['datasets']['ph'][] = $data->ph;
            $chartData['datasets']['suhu'][] = $data->suhu;
            $chartData['datasets']['kekeruhan'][] = $data->kekeruhan;
        }

        return response()->json($chartData);
    }

    public function checkWaterQuality()
    {
        $latestData = Sensor::latest()->first();

        if (! $latestData) {
            return response()->json(['status' => 'ok', 'message' => 'Belum ada data sensor.']);
        }

        $warnings = [];

        // Check parameter status using new QualityHelper
        $phStatus = QualityHelper::determineParameterStatus('ph', $latestData->ph);
        $suhuStatus = QualityHelper::determineParameterStatus('suhu', $latestData->suhu);
        $kekeruhanStatus = QualityHelper::determineParameterStatus('kekeruhan', $latestData->kekeruhan);

        if ($phStatus['status'] === 'Danger') {
            $warnings[] = "⚠️ {$phStatus['message']}";
        } elseif ($phStatus['status'] === 'Warning') {
            $warnings[] = "⚠️ {$phStatus['message']}";
        }

        if ($suhuStatus['status'] === 'Danger') {
            $warnings[] = "🔥 {$suhuStatus['message']}";
        } elseif ($suhuStatus['status'] === 'Warning') {
            $warnings[] = "🔥 {$suhuStatus['message']}";
        }

        if ($kekeruhanStatus['status'] === 'Danger') {
            $warnings[] = "💧 {$kekeruhanStatus['message']}";
        } elseif ($kekeruhanStatus['status'] === 'Warning') {
            $warnings[] = "💧 {$kekeruhanStatus['message']}";
        }

        return ! empty($warnings)
            ? response()->json(['status' => 'warning', 'messages' => $warnings])
            : response()->json(['status' => 'ok', 'message' => 'Semua parameter dalam kondisi normal.']);
    }

    public function checkWater()
    {
        $latest = Sensor::latest()->first();

        if (! $latest) {
            return response()->json(['status' => 'no_data', 'message' => 'Tidak ada data sensor.']);
        }

        $quality = $this->calculateFuzzyQuality($latest->ph, $latest->suhu, $latest->kekeruhan);

        // Get parameter statuses and overall status
        $parameterStatuses = QualityHelper::getAllParameterStatuses($latest->ph, $latest->suhu, $latest->kekeruhan);
        $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

        // Update sensor with quality and status information
        $latest->kualitas = $quality;
        $latest->ph_status = $parameterStatuses['ph']['status'];
        $latest->suhu_status = $parameterStatuses['suhu']['status'];
        $latest->kekeruhan_status = $parameterStatuses['kekeruhan']['status'];
        $latest->overall_status = $overallStatus['status'];

        $saved = $latest->save();

        if (! $saved) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan ke database.']);
        }

        // Get overall status based on individual parameter statuses
        $parameterStatuses = QualityHelper::getAllParameterStatuses($latest->ph, $latest->suhu, $latest->kekeruhan);
        $overallStatus = QualityHelper::getOverallStatus($parameterStatuses);

        return response()->json([
            'status' => strtolower($overallStatus['status']),
            'quality' => round($quality, 2),
            'messages' => [$overallStatus['message']],
            'parameter_statuses' => $parameterStatuses,
        ]);
    }

    private function trapezoid($x, $a, $b, $c, $d)
    {
        if ($x <= $a || $x >= $d) {
            return 0;
        } elseif ($x >= $b && $x <= $c) {
            return 1;
        } elseif ($x > $a && $x < $b) {
            return ($x - $a) / ($b - $a);
        } elseif ($x > $c && $x < $d) {
            return ($d - $x) / ($d - $c);
        }

        return 0;
    }

    private function output_membership($x, $label)
    {
        switch ($label) {
            case 'buruk':
                return $this->trapezoid($x, 0, 0, 20, 40);
            case 'sedang':
                return $this->trapezoid($x, 35, 45, 55, 70);
            case 'baik':
                return $this->trapezoid($x, 70, 85, 100, 100);
            default:
                return 0;
        }
    }

    public function calculateFuzzyQuality($ph, $suhu, $ntu)
    {
        // Fuzzifikasi input
        $ph_asam = $this->trapezoid($ph, 0, 0, 6.5, 7.5);
        $ph_netral = $this->trapezoid($ph, 7, 7.4, 8.4, 9);
        $ph_basa = $this->trapezoid($ph, 8.5, 10, 14, 14);

        $suhu_dingin = $this->trapezoid($suhu, 0, 0, 25, 28);
        $suhu_optimal = $this->trapezoid($suhu, 26, 27, 31, 34);
        $suhu_panas = $this->trapezoid($suhu, 32, 35, 45, 45);

        $keruh_jernih = $this->trapezoid($ntu, 0, 0, 3, 5);
        $keruh_optimal = $this->trapezoid($ntu, 3, 4, 39, 43);
        $keruh_keruh = $this->trapezoid($ntu, 40, 44, 100, 100);

        // Aturan fuzzy
        $rules = [
            ['asam', 'dingin', 'jernih', 'buruk'],
            ['asam', 'dingin', 'optimal', 'buruk'],
            ['asam', 'dingin', 'keruh', 'buruk'],
            ['asam', 'optimal', 'jernih', 'baik'],
            ['asam', 'optimal', 'optimal', 'sedang'],
            ['asam', 'optimal', 'keruh', 'buruk'],
            ['asam', 'panas', 'jernih', 'buruk'],
            ['asam', 'panas', 'optimal', 'buruk'],
            ['asam', 'panas', 'keruh', 'buruk'],

            ['netral', 'dingin', 'jernih', 'baik'],
            ['netral', 'dingin', 'optimal', 'sedang'],
            ['netral', 'dingin', 'keruh', 'buruk'],
            ['netral', 'optimal', 'jernih', 'baik'],
            ['netral', 'optimal', 'optimal', 'baik'],
            ['netral', 'optimal', 'keruh', 'sedang'],
            ['netral', 'panas', 'jernih', 'baik'],
            ['netral', 'panas', 'optimal', 'sedang'],
            ['netral', 'panas', 'keruh', 'buruk'],

            ['basa', 'dingin', 'jernih', 'buruk'],
            ['basa', 'dingin', 'optimal', 'buruk'],
            ['basa', 'dingin', 'keruh', 'buruk'],
            ['basa', 'optimal', 'jernih', 'baik'],
            ['basa', 'optimal', 'optimal', 'baik'],
            ['basa', 'optimal', 'keruh', 'buruk'],
            ['basa', 'panas', 'jernih', 'buruk'],
            ['basa', 'panas', 'optimal', 'buruk'],
            ['basa', 'panas', 'keruh', 'buruk'],
        ];

        // Hitung derajat output dari semua rule
        $rule_outputs = ['buruk' => 0, 'sedang' => 0, 'baik' => 0];
        foreach ($rules as $rule) {
            [$ph_key, $suhu_key, $keruh_key, $output_label] = $rule;
            $mu_ph = ${'ph_'.$ph_key};
            $mu_suhu = ${'suhu_'.$suhu_key};
            $mu_keruh = ${'keruh_'.$keruh_key};
            $mu = min($mu_ph, $mu_suhu, $mu_keruh);
            $rule_outputs[$output_label] += $mu;
        }

        // Defuzzifikasi dengan metode centroid
        $numerator = 0;
        $denominator = 0;

        for ($x = 0; $x <= 100; $x += 0.01) {
            $mu_buruk = min($rule_outputs['buruk'], $this->output_membership($x, 'buruk'));
            $mu_sedang = min($rule_outputs['sedang'], $this->output_membership($x, 'sedang'));
            $mu_baik = min($rule_outputs['baik'], $this->output_membership($x, 'baik'));

            $mu_total = max($mu_buruk, $mu_sedang, $mu_baik);

            $numerator += $x * $mu_total;
            $denominator += $mu_total;
        }

        if ($denominator == 0) {
            return 0;
        }

        $quality = $numerator / $denominator;

        return round($quality, 2);
    }

    public function updateMissingQuality()
    {
        $sensors = Sensor::whereNull('kualitas')->get();
        $count = 0;

        foreach ($sensors as $sensor) {
            if ($sensor->ph && $sensor->suhu && $sensor->kekeruhan) {
                $this->updateSensorQuality($sensor);
                $count++;
            }
        }

        return response()->json([
            'message' => "Berhasil update kualitas untuk $count data yang sebelumnya null.",
        ]);
    }

    private function updateSensorQuality($sensor)
    {
        $sensor->kualitas = $this->calculateFuzzyQuality($sensor->ph, $sensor->suhu, $sensor->kekeruhan);
        $sensor->save();
    }
}
