<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $fillable = ['kode_jenis', 'nama_jenis', 'format_nomor', 'template','status'];
    
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_jenis_surat');
    }
    
    public function SuratPenomoran()
    {
        return $this->hasMany(SuratPenomoran::class, 'id_jenis_surat');
    }
}