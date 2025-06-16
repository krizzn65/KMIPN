<?php

namespace Database\Factories;

use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition()
    {
        return [
            'ph' => $this->faker->randomFloat(2, 7.5, 8.5),
            'suhu' => $this->faker->randomFloat(2, 28, 32),
            'salinitas' => $this->faker->randomFloat(2, 15, 30),
            'kekeruhan' => $this->faker->randomFloat(2, 15, 30),
            'created_at' => now(), // Gunakan waktu saat ini
            'updated_at' => now(),
        ];
    }
}
