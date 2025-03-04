<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'no_anggota',
        'nama_klinik',
        'status',
        'id_provinsi',
        'id_kota',
        'id_kecamatan',
        'id_kelurahan',
        'nama_kontak',
        'email',
        'tlf',
        'status_pendaftar',
        'status_kepemilikan',
        'nama_pemilik_klinik',
        'bentuk_badan_hukum',
        'nama_badan_usaha',
        'bentuk_badan_usaha',
        'jenis_klinik',
        'alamat_klinik',
        'rt',
        'rw',
        'kode_pos',
        'tlf_klinik',
        'id_user',
        'id_fasilitas',
        'id_layanan',
        'no_ijin',
        'tgl_ijin',
        'tgl_akhir_ijin',
        'fasilitas_klinik',
        'logo',
        'data_umum',
        'sdm_klinik',
        'provider_asuransi',
        'foto_logo_klinik',
        'keterangan',
        'verifikasi_cabang',
        'verifikasi_pusat',
        'status_pembayaran',
        'sio'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth()->user()->id;
            $model->updated_by = Auth()->user()->id;
        });
        static::saving(function ($model) {
            $model->updated_by = Auth()->user()->id;
        });
    }
    
}
