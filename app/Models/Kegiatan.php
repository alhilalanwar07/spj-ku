<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subprogram_id',
        'kode_rekening_kegiatan',
        'nama_kegiatan',
    ];

    public function subprogram()
    {
        return $this->belongsTo(Subprogram::class);
    }

    public function subprograms()
    {
        return $this->hasMany(Subprogram::class);
    }

    public function subkegiatans()
    {
        return $this->hasMany(Subkegiatan::class);
    }
}
