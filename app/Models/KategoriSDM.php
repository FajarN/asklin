<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSDM extends Model
{
    use HasFactory;

    protected $table = 'sdm_kategori';

    protected $fillable = [
        'nama',
        'status',
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
