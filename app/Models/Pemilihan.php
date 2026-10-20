<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class Pemilihan extends Model
{
    use HasFactory;

    protected $table = 'pemilihan';

    protected $fillable = [
        'judul',
        'tingkat',
        'id_provinsi',
        'id_kota',
        'periode_mulai',
        'periode_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'periode_mulai' => 'integer',
        'periode_selesai' => 'integer',
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

        static::updating(function ($model) {
            $userId = auth()->id();
            if ($userId) {
                $model->updated_by = $userId;
            }
        });
    }

    public function kandidat()
    {
        return $this->hasMany(PemilihanKandidat::class, 'pemilihan_id')->orderBy('nomor_urut', 'asc');
    }

    public function suara()
    {
        return $this->hasMany(PemilihanSuara::class, 'pemilihan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'id_provinsi', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'id_kota', 'code');
    }

    public function getPeriodeTextAttribute()
    {
        return "{$this->periode_mulai} - {$this->periode_selesai}";
    }

    public function getWilayahNamaAttribute()
    {
        if ($this->tingkat === 'cabang') {
            return $this->kota ? $this->kota->name : ($this->id_kota ?? '-');
        }
        return $this->provinsi ? $this->provinsi->name : ($this->id_provinsi ?? '-');
    }

    public function getIsBerlangsungAttribute()
    {
        $now = now();
        return $this->status === 'aktif' && $now->gte($this->tanggal_mulai) && $now->lte($this->tanggal_selesai);
    }

    public function scopeForUser($query, $user)
    {
        if (!$user) {
            return $query;
        }

        if ($user->hasRole('Superadmin') || $user->hasRole('Admin Pusat')) {
            return $query;
        }

        if ($user->hasRole('Admin Daerah')) {
            return $query->where('id_provinsi', $user->provinsi);
        }

        if ($user->hasRole('Admin Cabang')) {
            return $query->where('tingkat', 'cabang')->where('id_kota', $user->kota);
        }

        if ($user->hasRole('Klinik')) {
            $anggota = $user->anggota;
            if ($anggota) {
                return $query->where(function ($q) use ($anggota) {
                    $q->where(function ($qc) use ($anggota) {
                        $qc->where('tingkat', 'cabang')->where('id_kota', $anggota->id_kota);
                    })->orWhere(function ($qd) use ($anggota) {
                        $qd->where('tingkat', 'daerah')->where('id_provinsi', $anggota->id_provinsi);
                    });
                });
            }
        }

        return $query;
    }
}
