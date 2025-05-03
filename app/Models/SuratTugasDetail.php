<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugasDetail extends Model
{
    protected $table = 'surat_tugas_detail';
    protected $fillable = ['id_surat_tugas', 'nama_pengurus', 'jabatan'];
    
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'id_surat_tugas');
    }
}