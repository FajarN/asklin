<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventKategori extends Model
{
    use HasFactory;

    protected $table = 'event_kategori';

    protected $fillable = [
        'nama',
    ];

    protected $casts = [
        'status' => 'boolean'
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
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }

    /**
     * Relationship dengan Event
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'id_kategori', 'id');
    }

    /**
     * Relationship dengan Event aktif
     */
    public function activeEvents()
    {
        return $this->hasMany(Event::class, 'id_kategori', 'id')
            ->where('status', 1);
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
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Method untuk mendapatkan jumlah event
     */
    public function getEventCountAttribute()
    {
        return $this->events()->count();
    }

    /**
     * Method untuk mendapatkan jumlah event aktif
     */
    public function getActiveEventCountAttribute()
    {
        return $this->activeEvents()->count();
    }
}
