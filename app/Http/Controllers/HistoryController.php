<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function getHistoryData()
    {
        try {
            // Ambil rata-rata per 1 menit dari tabel sensors
            $data = DB::table('sensors')
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as time_group'),
                    DB::raw('AVG(ph) as ph'),
                    DB::raw('AVG(suhu) as suhu'),
                    DB::raw('AVG(kekeruhan) as kekeruhan')
                )
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
                ->orderBy(DB::raw('MAX(created_at)'), 'desc') // urut dari data terbaru
                ->limit(100)
                ->get();

            // Kalau datanya kosong
            if ($data->isEmpty()) {
                return response()->json([]);
            }

            // Format ulang biar frontend gak error (ubah key time_group jadi created_at)
            $formattedData = $data->map(function ($item) {
                return [
                    'created_at' => $item->time_group,
                    'ph' => (float) $item->ph,
                    'suhu' => (float) $item->suhu,
                    'kekeruhan' => (float) $item->kekeruhan,
                ];
            });

            return response()->json($formattedData);
        } catch (\Exception $e) {
            Log::error('History data error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
