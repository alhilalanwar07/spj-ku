<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subkegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kegiatan_id',
        'kode_rekening_subkegiatan',
        'nama_subkegiatan',
        'anggaran',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function aktivitas()
    {
        return $this->hasMany(AktivitasPegawai::class);
    }


}
