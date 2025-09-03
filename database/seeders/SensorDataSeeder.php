<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sensor data for the last 24 hours
        for ($i = 0; $i < 24; $i++) {
            $time = Carbon::now()->subHours($i);
            
            Sensor::create([
                'ph' => rand(65, 85) / 10, // 6.5 to 8.5
                'suhu' => rand(260, 320) / 10, // 26.0 to 32.0
                'kekeruhan' => rand(0, 400) / 10, // 0.0 to 40.0
                'kualitas' => rand(500, 950) / 10, // 50.0 to 95.0
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }
        
        $this->command->info('Created 24 hours of test sensor data.');
    }
}