<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'golongan',
        'pangkat',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function aktivitasPegawais()
    {
        return $this->hasMany(AktivitasPegawai::class, 'pegawai_id');
    }
}
