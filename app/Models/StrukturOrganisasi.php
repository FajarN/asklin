<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StrukturOrganisasi extends Model
{
    use \App\Traits\RegionalScoping;

    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'id_tingkatan_pengurus',
        'id_provinsi',
        'id_kota',
        'nama_struktur',
        'periode',
        'tgl_muscab',
        'alamat_sekretariat',
        'email_sekretariat',
        'telp_sekretariat',
        'status',
        'is_locked',
        'catatan_revisi',
        'file_berita_acara',
        'diajukan_at',
        'diverifikasi_daerah_at',
        'diverifikasi_daerah_by',
        'disetujui_pusat_at',
        'disetujui_pusat_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'tgl_muscab' => 'date',
        'diajukan_at' => 'datetime',
        'diverifikasi_daerah_at' => 'datetime',
        'disetujui_pusat_at' => 'datetime',
    ];

    public function tingkatanPengurus(): BelongsTo
    {
        return $this->belongsTo(TingkatanPengurus::class, 'id_tingkatan_pengurus');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'id_provinsi', 'code');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'id_kota', 'code');
    }

    public function skPengurus(): HasOne
    {
        return $this->hasOne(SkPengurus::class, 'id_struktur_organisasi');
    }

    public function kelompokPengurus(): HasMany
    {
        return $this->hasMany(StrukturKelompokPengurus::class, 'id_struktur_organisasi')->orderBy('urutan', 'asc');
    }

    public function pengurus(): HasMany
    {
        return $this->hasMany(StrukturPengurus::class, 'id_struktur_organisasi')->orderBy('urutan', 'asc');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SkPengurusLog::class, 'id_struktur_organisasi')->orderBy('created_at', 'desc');
    }

    public function verifikatorDaerah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_daerah_by');
    }

    public function penyetujuPusat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_pusat_by');
    }
}
