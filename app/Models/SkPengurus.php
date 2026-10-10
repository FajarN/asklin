<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SkPengurus extends Model
{
    use HasFactory;

    protected $table = 'sk_pengurus';

    protected $fillable = [
        'id_struktur_organisasi',
        'id_ketua_umum',
        'tingkatan',
        'no_sk',
        'perihal',
        'no_rekomendasi_daerah',
        'tgl_rekomendasi_daerah',
        'tgl_musyawarah',
        'tgl_penetapan',
        'tgl_pelantikan',
        'kota_penetapan',
        'show_qr',
        'qr_token',
        'file_pdf',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'show_qr' => 'boolean',
        'tgl_rekomendasi_daerah' => 'date',
        'tgl_musyawarah' => 'date',
        'tgl_penetapan' => 'date',
        'tgl_pelantikan' => 'date',
    ];

    /**
     * Scope query SK berdasarkan hak akses wilayah login
     */
    public function scopeForCurrentUser($query)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$user) {
            return $query;
        }

        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            return $query->whereHas('strukturOrganisasi', function ($q) use ($user) {
                $q->where('id_kota', $user->kota);
            });
        }

        if ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            return $query->whereHas('strukturOrganisasi', function ($q) use ($user) {
                $q->where('id_provinsi', $user->provinsi);
            });
        }

        return $query;
    }

    /**
     * Auto generate qr_token unik saat record pertama kali dibuat
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->qr_token)) {
                $model->qr_token = (string) Str::uuid();
            }
        });
    }

    public function strukturOrganisasi(): BelongsTo
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'id_struktur_organisasi');
    }

    public function ketuaUmum(): BelongsTo
    {
        return $this->belongsTo(MasterKetuaUmum::class, 'id_ketua_umum');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SkPengurusLog::class, 'id_sk_pengurus')->orderBy('created_at', 'desc');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
