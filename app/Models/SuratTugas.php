<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    protected $table = 'surat_tugas';
    protected $dates = ['tgl_agenda'];
    protected $fillable = [
        'id_surat', 'asal_surat', 'nomor_asal_surat', 'agenda', 'hari', 'tgl_agenda', 
        'waktu_agenda', 'tempat_agenda'
    ];
    
    public function surat()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat');
    }

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat');
    }
        
    public function details()
    {
        return $this->hasMany(SuratTugasDetail::class, 'id_surat_tugas');
    }

    public function getTglAgendaFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->tgl_agenda)->format('d-m-Y');
    }
}