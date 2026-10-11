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
        'id_periode',
        'hotel',
        'nama_peserta',
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

    public function periode()
    {
        return $this->belongsTo(KonasPeriode::class, 'id_periode');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
