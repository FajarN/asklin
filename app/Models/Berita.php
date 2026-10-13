<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'id_kategori',
        'judul',
        'path',
        'tanggal',
        'konten',
        'lokasi',
        'thumb',
        'status',
        'kode_qr',
    ];

    public function kategori()
    {
        return $this->belongsTo(BeritaKategori::class, 'id_kategori');
    }

    public function images()
    {
        return $this->hasMany(BeritaImage::class, 'berita_id');
    }

    public function visits()
    {
        return $this->hasMany(BeritaVisit::class);
    }

    public function getTotalVisitsAttribute()
    {
        return $this->visits()->count();
    }

    public function getUniqueVisitsAttribute()
    {
        return $this->visits()->distinct('ip_address')->count('ip_address');
    }

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
