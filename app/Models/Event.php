<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'id_kategori',
        'judul',
        'path',
        'konten',
        'status',
        'gambar',
        'mulai',
        'selesai',
        'kategori',
        'id_provinsi',
        'id_kota',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
        'status' => 'string', // Karena di DB menggunakan enum('0','1')
    ];

    protected $dates = [
        'mulai',
        'selesai',
        'created_at',
        'updated_at'
    ];

    /**
     * Boot method untuk auto-fill created_by dan updated_by
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->id;
                $model->updated_by = auth()->user()->id;
            }

            // Auto generate slug jika path kosong
            if (empty($model->path)) {
                $model->path = Str::slug($model->judul);
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }

    /**
     * Relationship dengan EventKategori
     */
    public function kategori()
    {
        return $this->belongsTo(EventKategori::class, 'id_kategori', 'id');
    }

    /**
     * Relationship dengan User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Relationship dengan User (updater)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Scope untuk event aktif
     * Status '1' = aktif, '0' = tidak aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope untuk event terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'LIKE', "%{$search}%")
                ->orWhere('konten', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter kategori
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('id_kategori', $categoryId);
    }

    /**
     * Accessor untuk URL gambar
     */
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('assets/images/events/' . $this->gambar);
        }
        return asset('assets/images/default-event.jpg');
    }

    /**
     * Accessor untuk excerpt konten
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->konten), 150);
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status == '1' ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Accessor untuk created_at format Indonesia
     */
    public function getCreatedAtFormatAttribute()
    {
        return $this->created_at->locale('id')->diffForHumans();
    }

    /**
     * Accessor untuk tanggal mulai format Indonesia
     */
    public function getMulaiFormatAttribute()
    {
        return Carbon::parse($this->mulai)->locale('id')->isoFormat('D MMMM Y');
    }

    /**
     * Accessor untuk kategori nama
     */
    public function getKategoriNamaAttribute()
    {
        return $this->kategori ? $this->kategori->nama : 'Event';
    }

    /**
     * Method untuk check apakah event sudah selesai
     */
    public function isExpired()
    {
        return Carbon::now()->gt(Carbon::parse($this->selesai));
    }

    /**
     * Method untuk check apakah event sedang berlangsung
     */
    public function isOngoing()
    {
        $now = Carbon::now();
        return $now->gte(Carbon::parse($this->mulai)) && $now->lte(Carbon::parse($this->selesai));
    }

    /**
     * Method untuk mendapatkan event terkait
     */
    public function getRelatedEvents($limit = 3)
    {
        return static::where('id', '!=', $this->id)
            ->where('id_kategori', $this->id_kategori)
            ->active()
            ->latest()
            ->limit($limit)
            ->get();
    }
}
