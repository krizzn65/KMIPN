<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors';

    // Tambahkan 'kualitas' dan status fields ke dalam fillable
    protected $fillable = [
        'ph', 'suhu', 'kekeruhan', 'kualitas',
        'ph_status', 'suhu_status', 'kekeruhan_status', 'overall_status',
    ];
}
