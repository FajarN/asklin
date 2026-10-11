<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;

class RakernasPeserta extends Model
{
    use HasFactory;

    protected $table = 'rakernas_peserta';

    protected $fillable = [
        'id_periode',
        'no_registrasi',
        'nama_peserta',
        'nama_klinik',
        'jabatan',
        'no_hp',
        'email',
        'id_provinsi',
        'cabang',
        'alamat_klinik',
        'kebutuhan_khusus',
        'status_kehadiran',
        'status_pembayaran',
        'bukti_bayar',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke periode Rakernas
     */
    public function periode()
    {
        return $this->belongsTo(RakernasPeriode::class, 'id_periode');
    }

    /**
     * Relasi ke Provinsi (Laravolt)
     */
    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'id_provinsi', 'code');
    }

    /**
     * Relasi ke Kota/Kabupaten (Laravolt)
     */
    public function kota()
    {
        return $this->belongsTo(City::class, 'cabang', 'code');
    }

    /**
     * Boot model untuk generate otomatis no_registrasi unik
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_registrasi)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $model->no_registrasi = 'RKN-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

