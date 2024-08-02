<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subprogram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_id',
        'kode_rekening_subprogram',
        'nama_subprogram',
    ];


    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
