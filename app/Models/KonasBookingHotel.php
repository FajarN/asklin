<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasBookingHotel extends Model
{
    use HasFactory;

    protected $table = 'konas_booking_hotel';
    public $timestamps = false;

    protected $fillable = [
        'hotel',
        'jumlah',
        'extrabed',
        'harga_kasur',
        'harga_kamar',
        'id_user',
        'total_harga_kamar',
        'total_harga_extrabed',
        'total',
        'link_pembayaran',
        'status_pembayaran',
        'doc_no',
        'tanggal'
    ];
}
