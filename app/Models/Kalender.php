<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahun',
        'bulan',
        'tanggal_libur',
        'katerangan_libur',
    ];
}
