<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilihanKandidat extends Model
{
    use HasFactory;

    protected $table = 'pemilihan_kandidat';

    protected $fillable = [
        'pemilihan_id',
        'nomor_urut',
        'nama_calon',
        'gelar',
        'asal_klinik',
        'foto',
        'visi',
        'misi',
        'program_kerja',
        'total_suara',
    ];

    protected $casts = [
        'nomor_urut' => 'integer',
        'total_suara' => 'integer',
    ];

    public function pemilihan()
    {
        return $this->belongsTo(Pemilihan::class, 'pemilihan_id');
    }

    public function suara()
    {
        return $this->hasMany(PemilihanSuara::class, 'kandidat_id');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->gelar ? "{$this->nama_calon}, {$this->gelar}" : $this->nama_calon;
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path('images/pemilihan/' . $this->foto))) {
            return asset('images/pemilihan/' . $this->foto);
        }
        return null;
    }
}
