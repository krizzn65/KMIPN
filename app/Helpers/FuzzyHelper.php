<?php

namespace App\Helpers;

class FuzzyHelper
{
    // Fungsi membership PH
    private static function phMembership($ph)
    {
        return [
            'rendah' => $ph <= 7 ? 1 : ($ph < 7.5 ? (7.5 - $ph) / 0.5 : 0),
            'normal' => ($ph >= 7.5 && $ph <= 8.5) ? 1 : (($ph > 7 && $ph < 7.5) ? ($ph - 7) / 0.5 : (($ph > 8.5 && $ph < 9) ? (9 - $ph) / 0.5 : 0)),
            'tinggi' => $ph >= 9 ? 1 : ($ph > 8.5 ? ($ph - 8.5) / 0.5 : 0),
        ];
    }

    // Fungsi membership suhu
    private static function suhuMembership($suhu)
    {
        return [
            'rendah' => $suhu <= 26 ? 1 : ($suhu < 28 ? (28 - $suhu) / 2 : 0),
            'normal' => ($suhu >= 28 && $suhu <= 32) ? 1 : (($suhu > 26 && $suhu < 28) ? ($suhu - 26) / 2 : (($suhu > 32 && $suhu < 34) ? (34 - $suhu) / 2 : 0)),
            'tinggi' => $suhu >= 34 ? 1 : ($suhu > 32 ? ($suhu - 32) / 2 : 0),
        ];
    }

    // Fungsi membership kekeruhan (0–100%)
    private static function kekeruhanMembership($kekeruhan)
    {
        return [
            'jernih' => $kekeruhan <= 30 ? 1 : ($kekeruhan < 50 ? (50 - $kekeruhan) / 20 : 0),
            'normal' => ($kekeruhan >= 50 && $kekeruhan <= 80) ? 1 : (($kekeruhan > 30 && $kekeruhan < 50) ? ($kekeruhan - 30) / 20 : (($kekeruhan > 80 && $kekeruhan < 90) ? (90 - $kekeruhan) / 10 : 0)),
            'keruh' => $kekeruhan >= 90 ? 1 : ($kekeruhan > 80 ? ($kekeruhan - 80) / 10 : 0),
        ];
    }

    // Fungsi menghitung output fuzzy
    public static function hitungKualitasAir($ph, $suhu, $kekeruhan)
    {
        $phSet = self::phMembership($ph);
        $suhuSet = self::suhuMembership($suhu);
        $kekeruhanSet = self::kekeruhanMembership($kekeruhan);

        $rules = [
            ['ph' => 'normal', 'suhu' => 'normal', 'kekeruhan' => 'jernih', 'output' => 100],
            ['ph' => 'normal', 'suhu' => 'normal', 'kekeruhan' => 'normal', 'output' => 85],
            ['ph' => 'normal', 'suhu' => 'normal', 'kekeruhan' => 'keruh', 'output' => 60],
            ['ph' => 'rendah', 'suhu' => 'tinggi', 'kekeruhan' => 'keruh', 'output' => 40],
            ['ph' => 'tinggi', 'suhu' => 'tinggi', 'kekeruhan' => 'keruh', 'output' => 30],
            ['ph' => 'rendah', 'suhu' => 'rendah', 'kekeruhan' => 'jernih', 'output' => 70],
            ['ph' => 'tinggi', 'suhu' => 'normal', 'kekeruhan' => 'normal', 'output' => 75],
        ];

        $numerator = 0;
        $denominator = 0;

        foreach ($rules as $rule) {
            $alpha = min(
                $phSet[$rule['ph']],
                $suhuSet[$rule['suhu']],
                $kekeruhanSet[$rule['kekeruhan']]
            );

            $numerator += $alpha * $rule['output'];
            $denominator += $alpha;
        }

        return $denominator === 0 ? 0 : round($numerator / $denominator, 2);
    }
}
