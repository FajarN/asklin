<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;

class CekQRCodeController extends Controller
{
    public function cekqrcode($id)
    {

        $anggota = Anggota::select(
            'anggota.*',
            \DB::raw('GROUP_CONCAT(DISTINCT a.nama SEPARATOR ", ") as nama_fasilitas_klinik'),
            'b.name as kota',
            'c.name as provinsi'
        )
            ->leftjoin('fasilitas_klinik as a', \DB::raw("FIND_IN_SET(a.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
            ->leftjoin('indonesia_cities as b', 'b.code', '=', 'anggota.id_kota')
            ->leftjoin('indonesia_provinces as c', 'c.code', '=', 'anggota.id_provinsi')
            ->groupBy('anggota.id')
            ->where('anggota.id', $id)
            ->first();

        $id = $anggota->id;


        return view('cekqrcode', compact('anggota'));
    }
}
