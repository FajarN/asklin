<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakernasGaleri extends Model
{
    use HasFactory;

    protected $table = 'rakernas_galeri';

    protected $fillable = [
        'id_periode',
        'judul',
        'foto',
        'kategori',
        'deskripsi',
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

