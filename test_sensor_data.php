<?php

use App\Models\Sensor;
use Illuminate\Support\Carbon;

// Create some test sensor data for the last 24 hours
for ($i = 0; $i < 24; $i++) {
    $time = Carbon::now()->subHours($i);
    
    $sensor = new Sensor();
    $sensor->ph = rand(65, 85) / 10; // 6.5 to 8.5
    $sensor->suhu = rand(260, 320) / 10; // 26.0 to 32.0
    $sensor->kekeruhan = rand(0, 400) / 10; // 0.0 to 40.0
    $sensor->kualitas = rand(500, 950) / 10; // 50.0 to 95.0
    $sensor->created_at = $time;
    $sensor->updated_at = $time;
    
    $sensor->save();
    
    echo "Created sensor data for: " . $time->format('Y-m-d H:i:s') . "\n";
}

echo "Test data created successfully!\n";