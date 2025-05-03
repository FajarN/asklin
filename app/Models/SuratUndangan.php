<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratUndangan extends Model
{
    protected $table = 'surat_undangan';
    protected $fillable = [
        'id_surat', 'nama_penerima', 'alamat_penerima', 'salam_pembuka',
        'isi_surat', 'salam_penutup', 'judul_acara', 'tujuan_acara', 'hari', 'tgl_acara','waktu_acara', 'lokasi_acara', 'agenda_acara', 'informasi_tambahan'
    ];
    
    public function surat()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat');
    }

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat');
    }
}