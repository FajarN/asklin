<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasMasterTipeHotel extends Model
{
    use HasFactory;

    protected $table = 'konas_master_hotel_tipe';
    public $timestamps = false;

    protected $fillable = [
        'id_hotel',
        'tipe',
        'harga',
        'extrabed',
        'stok'
    ];
}
