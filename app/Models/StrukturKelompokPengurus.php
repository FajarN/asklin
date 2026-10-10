<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukturKelompokPengurus extends Model
{
    protected $table = 'struktur_kelompok_pengurus';

    protected $fillable = [
        'nama_kelompok',
        'urutan',
        'keterangan',
    ];

    public function strukturPengurus(): HasMany
    {
        return $this->hasMany(StrukturPengurus::class, 'id_kelompok');
    }
}
