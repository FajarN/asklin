<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\{City, District, Village};
    
class WilayahController extends Controller
{
    public function getKota(Request $request)
    {
        $id_provinsi = $request->id_provinsi;

        $kota = City::where('province_code', $id_provinsi)->get();

        echo "<option value=''>==Pilih Kota/Kabupaten==</option>";
        foreach ($kota as $k){
            echo "<option value='$k->code'>$k->name</option>";
        }
    }

    public function getKecamatan(Request $request)
    {
        $id_kota = $request->id_kota;

        $kecamatan = District::where('city_code', $id_kota)->get();

        echo "<option value=''>==Pilih Kecamatan==</option>";
        foreach ($kecamatan as $k){
            echo "<option value='$k->code'>$k->name</option>";
        }
    }

    public function getKelurahan(Request $request)
    {
        $id_kecamatan = $request->id_kecamatan;

        $kelurahan = Village::where('district_code', $id_kecamatan)->get();

        echo "<option value=''>==Pilih Kelurahan/Desa==</option>";
        foreach ($kelurahan as $k){
            echo "<option value='$k->code'>$k->name</option>";
        }
    }
}