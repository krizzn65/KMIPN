<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvgSensor extends Model
{
    use HasFactory;

    protected $table = 'avgsensors';

    protected $fillable = [
        'ph',
        'temp_c',
        'kekeruhan',
        'last_ts',
        'kualitas'
    ];

    protected $casts = [
        'ph' => 'float',
        'temp_c' => 'float',
        'kekeruhan' => 'float',
        'last_ts' => 'datetime',
    ];
}
