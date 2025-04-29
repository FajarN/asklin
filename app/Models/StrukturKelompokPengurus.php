<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukturKelompokPengurus extends Model
{
    protected $table = 'struktur_kelompok_pengurus';

    protected $fillable = [
        'id_struktur_organisasi',
        'nama_kelompok',
        'urutan',
        'keterangan',
    ];

    public function strukturOrganisasi(): BelongsTo
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'id_struktur_organisasi');
    }

    public function strukturPengurus(): HasMany
    {
        return $this->hasMany(StrukturPengurus::class, 'id_kelompok');
    }
}
