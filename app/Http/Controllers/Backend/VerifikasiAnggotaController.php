<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class VerifikasiAnggotaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:verifikasi-anggota', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.verifikasi_anggota.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name',
                'indonesia_villages.name as kelurahan',
                'indonesia_districts.name as kecamatan',
                'indonesia_provinces.name as provinsi',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
            )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
                ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->groupBy('anggota.id')
                ->whereNotIn('anggota.status', ['approved'])
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })
                ->when(Auth::user()->hasRole('Sekjen'), function ($query) use ($request) {
                    $query->where('anggota.status', 'Verifikasi Sekjen');
                })
                ->when(Auth::user()->hasRole('Ketua Umum'), function ($query) use ($request) {
                    $query->where('anggota.status_pembayaran', '1');
                })
                ->when(Auth::user()->hasRole('Bendahara'), function ($query) use ($request) {
                    $query->where('anggota.status', 'Verifikasi Bendahara');
                })
                ->get();
            // return Datatables::of($data)
            //     ->filter(function ($instance) use ($request) {
            //         if (!empty($request->get('search'))) {
            //             $instance->collection = $instance->collection->filter(function ($data) use ($request) {
            //                 if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
            //                     return true;
            //                 }
            //                 return false;
            //             });
            //         }
            //     })
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $searchQuery = Str::lower($request->get('search'));
                        $instance->collection = $instance->collection->filter(function ($data) use ($searchQuery) {
                            if (Str::contains(Str::lower($data['nama_klinik']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['no_anggota']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['name']), $searchQuery)) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', 'backend.verifikasi_anggota.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function verify($id)
    {
        $anggota = Anggota::select('anggota.*', 'indonesia_cities.name as kota', 'indonesia_districts.name as kecamatan', 'indonesia_villages.name as kelurahan', 'indonesia_provinces.name as provinsi')
            ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->where('anggota.id', $id)
            ->first();


        return view('backend.verifikasi_anggota.verify', compact('anggota'));
    }

    public function verifyUpdate(Request $request, $id)
    {
        $id = $request->id;
        $anggota = Anggota::where('id', $id)->first();
        $count = Anggota::where('status', 'approved')->count();
        $kliniknya = explode(',', $anggota->id_fasilitas);

        if (in_array("2", $kliniknya)) {
            $jr = '2';
        }
        $jr = 1;

        if ($anggota->jenis_klinik == 'Pratama') {
            $jk = 1;
        } else {
            $jk = 2;
        }

        if ($request->status == 'approved') {
            $numor = $count + 1;
            $counts = $this->ceknourut($numor);
            $nourut = str_pad($counts, 5, "0", STR_PAD_LEFT);
            $no_anggota = $anggota->id_kelurahan . '-' . $nourut . '.' . $jk . '.' . $jr;
            $verifikasi_pusat = date('Y-m-d H:i:s');
            $verifikasi_cabang = $anggota->verifikasi_cabang;
        } elseif ($request->status == 'Verifikasi Sekjen') {
            $no_anggota = NULL;
            $verifikasi_cabang = date('Y-m-d H:i:s');
            $verifikasi_pusat = NULL;
        } else {
            $no_anggota = NULL;
            $verifikasi_pusat = NULL;
            $verifikasi_cabang = NULL;
        }

        Anggota::where('id', $id)->update([
            'no_anggota' => $no_anggota,
            'data_umum' => $request->data_umum,
            'sdm_klinik' => $request->sdm_klinik,
            'provider_asuransi' => $request->sdm_asuransi,
            'foto_logo_klinik' => $request->foto_logo_klinik,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'verifikasi_cabang' => $verifikasi_cabang,
            'verifikasi_pusat' => $verifikasi_pusat

        ]);

        return redirect()->route('verifikasi_anggota.verify', $id)->with('success', 'Anggota berhasil diperbarui');
    }

    public function verifyBendahara(Request $request)
    {
        $id = $request->id;
        $data = Anggota::where('id', $id)->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return Response()->json($data);
    }

    public function editBendahara(Request $request)
    {
        $data = Anggota::find($request->id);

        return Response()->json($data);
    }

    public function ceknourut($no = 0)
    {
        $reserved = array(1, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100);
        if (in_array($no, $reserved)) {
            $no = $no + 1;
            return $this->ceknourut($no);
        } else {
            return $no;
        }
    }

    public function sdm_pjk(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '1'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_dp(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '2'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_tk(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '3'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_tkl(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '4'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_lain(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '5'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_rumah_sakit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = RumahSakit::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_asuransi(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Asuransi::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_foto(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = FotoKlinik::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
