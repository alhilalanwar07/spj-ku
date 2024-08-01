<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AktivitasPegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aktivitas_id',
        'pegawai_id',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
