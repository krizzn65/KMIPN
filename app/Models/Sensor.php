<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors';

    // Tambahkan 'kualitas' ke dalam fillable
    protected $fillable = ['ph', 'suhu', 'salinitas', 'kekeruhan', 'kualitas'];
}
