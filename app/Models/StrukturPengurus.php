<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrukturPengurus extends Model
{
    protected $table = 'struktur_pengurus';

    protected $fillable = [
        'id_struktur_organisasi',
        'id_kelompok',
        'jabatan',
        'keterangan',
        'parent_id',
        'nama_lengkap',
        'no_telp',
        'email',
        'foto_pengurus',
        'urutan',
        'status',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StrukturPengurus::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(StrukturPengurus::class, 'parent_id');
    }

}


