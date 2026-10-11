<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakernasUndangan extends Model
{
    use HasFactory;

    protected $table = 'rakernas_undangan';

    protected $fillable = [
        'id_periode',
        'nama',
        'instansi',
        'jabatan',
        'kategori',
        'foto_logo',
        'status_kehadiran',
        'urutan',
    ];

    /**
     * Relasi ke periode Rakernas
     */
    public function periode()
    {
        return $this->belongsTo(RakernasPeriode::class, 'id_periode');
    }
}

