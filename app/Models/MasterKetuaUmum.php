<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterKetuaUmum extends Model
{
    use HasFactory;

    protected $table = 'master_ketua_umum';

    protected $fillable = [
        'nama_lengkap',
        'periode_mulai',
        'periode_selesai',
        'file_ttd',
        'status',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke seluruh SK Pengurus yang disahkan oleh Ketua Umum ini
     */
    public function skPengurus(): HasMany
    {
        return $this->hasMany(SkPengurus::class, 'id_ketua_umum');
    }

    /**
     * Helper untuk mengambil Ketua Umum aktif saat ini
     */
    public static function getAktif()
    {
        return self::where('status', 'aktif')->latest()->first();
    }
}

