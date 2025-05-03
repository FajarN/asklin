<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPenomoran extends Model
{
    protected $table = 'surat_penomoran';
    protected $fillable = ['id_jenis_surat', 'tahun', 'nomor_terakhir'];
    
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat');
    }
}