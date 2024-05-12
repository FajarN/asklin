<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    use HasFactory;

    protected $table = 'sdm_asuransi';

    protected $fillable = [
        'id_kategori',
        'id_klinik',
        'nama',
        'nama_kontak',
        'alamat',
        'telepon',
        'bukti'
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
