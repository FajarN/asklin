<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'id_tingkatan_pengurus',
        'id_provinsi',
        'id_kota',
        'nama_struktur',
        'periode',
        'tgl_muscab',
        'status',
    ];

    public function tingkatanPengurus(): BelongsTo
    {
        return $this->belongsTo(TingkatanPengurus::class, 'id_tingkatan_pengurus');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'id_provinsi');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'id_kota');
    }

}
