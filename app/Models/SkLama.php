<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class SkLama extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sk_lama';

    protected $fillable = [
        'no_sk',
        'judul',
        'tingkatan',
        'id_provinsi',
        'id_kota',
        'periode',
        'tahun_mulai',
        'tahun_selesai',
        'tgl_penetapan',
        'file_sk',
        'file_nama_asli',
        'file_ekstensi',
        'file_size',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_penetapan' => 'date',
        'tahun_mulai' => 'integer',
        'tahun_selesai' => 'integer',
        'file_size' => 'integer',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'id_provinsi', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'id_kota', 'code');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Hitung tahun selesai efektif dari field tahun_selesai atau string periode
     */
    public function getEffectiveTahunSelesaiAttribute()
    {
        if ($this->tahun_selesai) {
            return (int) $this->tahun_selesai;
        }

        if ($this->periode && preg_match('/(\d{4})\s*$/', trim($this->periode), $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Periksa apakah SK sudah expired
     */
    public function getIsExpiredAttribute()
    {
        $endYear = $this->effective_tahun_selesai;
        if ($endYear) {
            return (int) date('Y') > $endYear;
        }
        return false;
    }

    /**
     * Status masa berlaku display text & badge
     */
    public function getStatusMasaBerlakuBadgeAttribute()
    {
        $endYear = $this->effective_tahun_selesai;

        if (!$endYear) {
            return '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-700">Periode Belum Ditentukan</span>';
        }

        $currentYear = (int) date('Y');

        if ($currentYear > $endYear) {
            $diffYear = $currentYear - $endYear;
            return '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30 flex items-center gap-1 w-fit"><i class="fas fa-history text-[10px]"></i> Expired (' . $endYear . ') &bull; ' . $diffYear . ' th lalu</span>';
        }

        return '<span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 flex items-center gap-1 w-fit"><i class="fas fa-check-circle text-[10px]"></i> Masih Aktif (s/d ' . $endYear . ')</span>';
    }

    /**
     * URL file yang diupload
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/sk_lama/' . $this->file_sk);
    }

    /**
     * Scope sesuai kewenangan user login
     */
    public function scopeForCurrentUser($query)
    {
        $user = Auth::user();
        if (!$user) {
            return $query;
        }

        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            return $query->where(function ($q) use ($user) {
                $q->where('id_kota', $user->kota)
                  ->orWhere('tingkatan', 'cabang');
            });
        }

        if ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            return $query->where(function ($q) use ($user) {
                $q->where('id_provinsi', $user->provinsi)
                  ->orWhere('tingkatan', 'daerah');
            });
        }

        return $query;
    }
}
