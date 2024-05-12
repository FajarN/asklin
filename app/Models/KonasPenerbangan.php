<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasPenerbangan extends Model
{
    use HasFactory;

    protected $table = 'konas_penerbangan';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'dari',
        'tujuan',
        'pesawat',
        'id_user'
    ];
}
