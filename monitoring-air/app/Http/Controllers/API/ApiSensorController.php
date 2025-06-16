<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class ApiSensorController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'ph' => 'required|numeric',
            'suhu' => 'required|numeric',
            'salinitas' => 'required|numeric',
            'kekeruhan' => 'required|numeric',
        ]);

        // Simpan data ke database
        Sensor::create([
            'ph' => $request->ph,
            'suhu' => $request->suhu,
            'salinitas' => $request->salinitas,
            'kekeruhan' => $request->kekeruhan,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
