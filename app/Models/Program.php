<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_rekening_program',
        'nama_program',
        'istilah_program',
    ];

    public function subprograms()
    {
        return $this->hasMany(Subprogram::class);
    }

}
