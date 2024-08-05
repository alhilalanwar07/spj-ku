<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinasluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'aktivitas_id',
        'pegawai_id',
        'tanggal',
        'bulan',
        'tahun',
        'catatan',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
