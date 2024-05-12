<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SDM extends Model
{
    use HasFactory;

    protected $table = 'sdm';

    protected $fillable = [
        'id_klinik',
        'nama',
        'status',
        'id_kategori_sdm',
        'peranan',
        'nama_dokter',
        'npa_idi',
        'no_str',
        'no_sip',
        'tgl_akhir_sip',
        'no_tlf',
        'no_sib_sik',
        'tgl_akhir_str',
        'ket_sib_sik',
        'farmasi_apoteker',
        'ijazah_terakhir',
        'jabatan',
        'email_dokter',
        'file_str',
        'file_sip'
    ];

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
