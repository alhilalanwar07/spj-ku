<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kalender extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahun',
        'bulan',
        'tanggal_libur',
        'katerangan_libur',
        'pegawai_id',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
