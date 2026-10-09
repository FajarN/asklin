<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use App\Models\{Anggota};
use Illuminate\Support\Facades\DB;
use Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $stats = Anggota::selectRaw("
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
            SUM(CASE WHEN status = 'ditolak pusat' THEN 1 ELSE 0 END) as ditolak,
            SUM(CASE WHEN status = 'Perlu Perbaikan' THEN 1 ELSE 0 END) as perbaikan,
            SUM(CASE WHEN status = 'Diverifikasi Cabang' THEN 1 ELSE 0 END) as diverifikasi_cabang,
            SUM(CASE WHEN status = 'Terverifikasi Cabang' THEN 1 ELSE 0 END) as terverifikasi_cabang,
            SUM(CASE WHEN status = 'waiting' THEN 1 ELSE 0 END) as waiting,
            SUM(CASE WHEN status = 'create_dokter' THEN 1 ELSE 0 END) as create_dokter
        ")
        ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
            $query->where('id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
            $query->where('id_provinsi', Auth::user()->provinsi);
        })
        ->first();

        $approved = (int) ($stats->approved ?? 0);
        $proses = (int) ($stats->proses ?? 0);
        $ditolak = (int) ($stats->ditolak ?? 0);
        $perbaikan = (int) ($stats->perbaikan ?? 0);
        $diverifikasi_cabang = (int) ($stats->diverifikasi_cabang ?? 0);
        $terverifikasi_cabang = (int) ($stats->terverifikasi_cabang ?? 0);
        $waiting = (int) ($stats->waiting ?? 0);
        $create_dokter = (int) ($stats->create_dokter ?? 0);
        $recent = Anggota::orderBy('updated_at', 'DESC')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->take(5)->get();

        $perKepemilikan = Anggota::select('status_kepemilikan', DB::raw('count(*) as total'))
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($q) {
                    $q->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($q) {
                    $q->where('id_provinsi', Auth::user()->provinsi);
                })
                ->groupBy('status_kepemilikan')
                ->pluck('total', 'status_kepemilikan');

        $provinsi = Province::get();

        return view('backend.dashboard', compact('approved', 'waiting', 'create_dokter', 'terverifikasi_cabang', 'recent', 'proses', 'perbaikan', 'diverifikasi_cabang', 'terverifikasi_cabang', 'ditolak','perKepemilikan','provinsi'));
    }


    public function getKota(Request $request)
    {
        $kota = City::where('province_code', $request->id_provinsi)->get();

        $options = '<option value="">Pilih Kota/Kabupaten</option>';
        foreach ($kota as $k) {
            $options .= '<option value="'.$k->code.'">'.$k->name.'</option>';
        }
        return $options;
    }
        
    public function getStatistikWilayah(Request $request)
    {
        $query = Anggota::query();

        $query->when(Auth::user()->hasRole('Admin Cabang'), function ($q) {
            $q->where('anggota.id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($q) {
            $q->where('anggota.id_provinsi', Auth::user()->provinsi);
        });

        if ($request->provinsi && !$request->kota) {
            $statistik = (clone $query)
                ->select('indonesia_cities.name as kota', DB::raw('count(*) as total'))
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->where('anggota.id_provinsi', $request->provinsi)
                ->groupBy('indonesia_cities.name')
                ->get();

        } elseif ($request->provinsi && $request->kota) {
            $statistik = (clone $query)
                ->select('indonesia_cities.name as kota', DB::raw('count(*) as total'))
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->where('anggota.id_kota', $request->kota)
                ->groupBy('indonesia_cities.name')
                ->get();

        } else {
            $statistik = (clone $query)
                ->select('indonesia_provinces.name as provinsi', DB::raw('count(*) as total'))
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->groupBy('indonesia_provinces.name')
                ->get();
        }

        return response()->json($statistik);
    }

    public function getStatistikJenisKlinik()
    {
        $query = Anggota::query();

        $query->when(Auth::user()->hasRole('Admin Cabang'), function ($q) {
            $q->where('id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($q) {
            $q->where('id_provinsi', Auth::user()->provinsi);
        });

        $utama = (clone $query)->where('jenis_klinik', 'Utama')->count();
        $pratama = (clone $query)->where('jenis_klinik', 'Pratama')->count();

        return response()->json([
            'labels' => ['Utama', 'Pratama'],
            'data' => [$utama, $pratama]
        ]);
    }

    public function getStatistikBadanHukum()
    {
        $query = Anggota::query();

        $query->when(Auth::user()->hasRole('Admin Cabang'), function ($q) {
            $q->where('id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($q) {
            $q->where('id_provinsi', Auth::user()->provinsi);
        });

        $pt = (clone $query)->where('bentuk_badan_hukum', 'PT')->count();
        $yayasan = (clone $query)->where('bentuk_badan_hukum', 'Yayasan')->count();
        $koperasi = (clone $query)->where('bentuk_badan_hukum', 'Koperasi')->count();
        $cv = (clone $query)->where('bentuk_badan_usaha', 'CV')->count();

        return response()->json([
            'labels' => ['PT', 'CV', 'Yayasan', 'Koperasi'],
            'data' => [$pt, $cv, $yayasan, $koperasi]
        ]);
    }


}
