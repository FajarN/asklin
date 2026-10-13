<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaKategori extends Model
{
    use HasFactory;

    protected $table = 'berita_kategori';

    protected $fillable = [
        'nama'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $userId = auth()->id();
            if ($userId) {
                $model->created_by = $model->created_by ?? $userId;
                $model->updated_by = $model->updated_by ?? $userId;
            }
        });
        static::saving(function ($model) {
            $userId = auth()->id();
            if ($userId) {
                $model->updated_by = $userId;
            }
        });
    }
}
