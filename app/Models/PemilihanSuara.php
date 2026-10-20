<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilihanSuara extends Model
{
    use HasFactory;

    protected $table = 'pemilihan_suara';

    protected $fillable = [
        'pemilihan_id',
        'kandidat_id',
        'anggota_id',
        'user_id',
        'waktu_vote',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'waktu_vote' => 'datetime',
    ];

    public function pemilihan()
    {
        return $this->belongsTo(Pemilihan::class, 'pemilihan_id');
    }

    public function kandidat()
    {
        return $this->belongsTo(PemilihanKandidat::class, 'kandidat_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
