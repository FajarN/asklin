<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakernasPeriode extends Model
{
    use HasFactory;

    protected $table = 'rakernas_periode';

    protected $fillable = [
        'nama',
        'rakernas_ke',
        'periode',
        'tahun',
        'tema',
        'subtema',
        'tempat',
        'alamat_venue',
        'tanggal_mulai',
        'tanggal_selesai',
        'tgl_tutup_daftar',
        'biaya_registrasi',
        'banner_img',
        'video_url',
        'rundown_file',
        'deskripsi',
        'sambutan_ketum',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tgl_tutup_daftar' => 'date',
    ];

    /**
     * Scope untuk mengambil periode/penyelenggaraan yang sedang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Relasi ke peserta Rakernas
     */
    public function peserta()
    {
        return $this->hasMany(RakernasPeserta::class, 'id_periode');
    }

    /**
     * Relasi ke pihak yang diundang (Kemenkes, BPJS, Pemda, Sponsor)
     */
    public function undangan()
    {
        return $this->hasMany(RakernasUndangan::class, 'id_periode')->orderBy('urutan', 'asc');
    }

    /**
     * Relasi ke galeri venue dan dokumentasi
     */
    public function galeri()
    {
        return $this->hasMany(RakernasGaleri::class, 'id_periode')->orderBy('urutan', 'asc');
    }

    /**
     * Format rentang tanggal pelaksanaan yang rapi
     */
    public function getTanggalFormattedAttribute()
    {
        if (!$this->tanggal_mulai) return '-';
        if (!$this->tanggal_selesai || $this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
            return $this->tanggal_mulai->translatedFormat('d F Y');
        }

        if ($this->tanggal_mulai->format('m Y') === $this->tanggal_selesai->format('m Y')) {
            return $this->tanggal_mulai->translatedFormat('d') . ' - ' . $this->tanggal_selesai->translatedFormat('d F Y');
        }

        return $this->tanggal_mulai->translatedFormat('d M') . ' - ' . $this->tanggal_selesai->translatedFormat('d M Y');
    }

    /**
     * Fallback / alias untuk periode kepengurusan
     */
    public function getPeriodeKepengurusanAttribute()
    {
        return $this->periode ?? '2023-2028';
    }
}
