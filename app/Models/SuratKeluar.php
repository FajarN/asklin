<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $casts = [
        'tgl_surat' => 'date',
    ];

    protected $fillable = [
        'id_jenis_surat', 'tgl_surat', 'no_surat', 'perihal', 'kode_qr', 
        'created_by', 'updated_by', 'status'
    ];
    
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
    
    public function suratTugas()
    {
        return $this->hasOne(SuratTugas::class, 'id_surat');
    }
    
    public function suratUndangan()
    {
        return $this->hasOne(SuratUndangan::class, 'id_surat');
    }
}