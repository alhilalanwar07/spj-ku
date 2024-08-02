<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'penyelenggara',
        'keterangan',
        'subkegiatan_id',
    ];

    public function subkegiatan()
    {
        return $this->belongsTo(Subkegiatan::class);
    }

    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class);
    }

}
