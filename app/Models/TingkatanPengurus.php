<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TingkatanPengurus extends Model
{
    protected $table = 'tingkatan_pengurus';

    protected $fillable = [
        'nama_tingkatan',
    ];

    public function strukturOrganisasi(): HasMany
    {
        return $this->hasMany(StrukturOrganisasi::class, 'id_tingkatan_pengurus');
    }
}
