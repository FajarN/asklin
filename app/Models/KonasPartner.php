<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasPartner extends Model
{
    use HasFactory;

    protected $table = 'konas_partner';
    public $timestamps = false;

    protected $fillable = [
        'id_periode',
        'nama',
        'photo',
        'link'
    ];

    public function periode()
    {
        return $this->belongsTo(KonasPeriode::class, 'id_periode');
    }
}
