<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPusat extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_pusat';

    protected $fillable = [
        'id_kota',
        'id_provinsi',
        'status',
        'jumlah_klinik',
        'bukti',
        'total',
        'jenis_pembayaran',
        'dari',
        'sampai',
        'keterangan'
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
