<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Sertifikat, Anggota};

class CekQRSertifikatController extends Controller
{
    public function cekqrsertifikat($id)
    {

        $anggota = Sertifikat::select(
            'sertifikat.*',
            'a.nama_klinik',
            'a.no_anggota',
            'a.updated_at',
            'a.nama_kontak',
            'a.alamat_klinik',
            'a.rt',
            'a.rw',
            'b.name as kelurahan',
            'c.name as kecamatan',
            'd.name as kota',
            'e.name as provinsi',
            'a.id_kota',
            'a.id_provinsi'
        )
            ->leftJoin('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('sertifikat.id', $id)
            ->first();

        $id = $anggota->id;


        return view('cekqrsertifikat', compact('anggota'));
    }
}
