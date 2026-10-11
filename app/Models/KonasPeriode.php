<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasPeriode extends Model
{
    use HasFactory;

    protected $table = 'konas_periode';

    protected $fillable = [
        'nama',
        'tahun_mulai',
        'tahun_selesai',
        'periode',
        'tema',
        'subtema',
        'tempat',
        'tanggal_mulai',
        'tanggal_selesai',
        'tgl_tutup_daftar',
        'biaya_registrasi',
        'banner_img',
        'video_url',
        'tor_file',
        'sponsorship_file',
        'deskripsi',
        'sambutan_ketum',
        'sambutan_panitia',
        'sambutan_gubernur',
        'sambutan_ketua_wilayah',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tgl_tutup_daftar' => 'date',
    ];

    /**
     * Scope untuk mengambil periode yang sedang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Relasi ke delegasi peserta KONAS
     */
    public function peserta()
    {
        return $this->hasMany(KonasPeserta::class, 'id_periode');
    }

    /**
     * Relasi ke master hotel KONAS
     */
    public function hotels()
    {
        return $this->hasMany(KonasMasterHotel::class, 'id_periode');
    }

    /**
     * Relasi ke booking hotel KONAS
     */
    public function bookings()
    {
        return $this->hasMany(KonasBookingHotel::class, 'id_periode');
    }

    /**
     * Relasi ke penerbangan KONAS
     */
    public function penerbangan()
    {
        return $this->hasMany(KonasPenerbangan::class, 'id_periode');
    }

    /**
     * Relasi ke partner / sponsor KONAS
     */
    public function partners()
    {
        return $this->hasMany(KonasPartner::class, 'id_periode');
    }
}

