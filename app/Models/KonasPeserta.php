<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasPeserta extends Model
{
    use HasFactory;

    protected $table = 'konas_peserta';

    protected $fillable = [
        'no_anggota',
        'nama_peserta',
        'nama_sertifikat',
        'nik',
        'tgl_lahir',
        'detail',
        'no_hp',
        'email',
        'foto',
        'keanggotaan',
        'nama_klinik',
        'alamat_klinik',
        'status',
        'cabang',
        'id_provinsi',
        'surat_mandat',
        'komisi',
        'link_pembayaran',
        'status_pembayaran',
        'doc_no',
        'biaya_pendaftaran',
        'status_pembayaran_hotel',
        'biaya_hotel'
    ];
}