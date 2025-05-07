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
        $approved = Anggota::where('status', 'approved')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $proses = Anggota::where('status', 'proses')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $ditolak = Anggota::where('status', 'ditolak pusat')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $perbaikan = Anggota::where('status', 'Perlu Perbaikan')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $diverifikasi_cabang = Anggota::where('status', 'Diverifikasi Cabang')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $terverifikasi_cabang = Anggota::where('status', 'Terverifikasi Cabang')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $waiting = Anggota::where('status', 'waiting')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $create_dokter = Anggota::where('status', 'create_dokter')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
        $terverifikasi_cabang = Anggota::where('status', 'Terverifikasi Cabang')->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })->count();
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
        $kota = Regency::where('province_code', $request->id_provinsi)->get();

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
