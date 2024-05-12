<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranEvent extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_event';

    protected $fillable = [
        'nama_peserta',
        'keanggotaaan',
        'nama_klinik',
        'alamat',
        'id_provinsi',
        'id_kota',
        'id_kecamatan',
        'id_kelurahan',
        'email',
        'tlf',
        'id_kota',
        'nama_hotel',
        'jumlah_kamar',
        'tgl_masuk',
        'tgl_keluar'
    ];

    // protected static function booted()
    // {
    //     static::creating(function ($model) {
    //         $model->created_by = Auth()->user()->id;
    //         $model->updated_by = Auth()->user()->id;
    //     });
    //     static::saving(function ($model) {
    //         $model->updated_by = Auth()->user()->id;
    //     });
    // }
}
