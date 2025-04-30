<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik, Sertifikat};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;

class ExpiredSIOController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:verifikasi', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.expired_sio.index');
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
                ->where('anggota.status', 'approved')
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
                ->when($request->filter_expired, function ($query) use ($request) {
                    $today = Carbon::today();
                    $besok = Carbon::tomorrow();
                    $seminggu = Carbon::today()->addDays(7);
                    $sebulan = Carbon::today()->addDays(30);

                    if ($request->filter_expired == 'expired') {
                        $query->whereDate('tgl_akhir_ijin', '<', $today);
                    } elseif ($request->filter_expired == 'besok') {
                        $query->whereDate('tgl_akhir_ijin', '=', $besok);
                    } elseif ($request->filter_expired == 'seminggu') {
                        $query->whereDate('tgl_akhir_ijin', '>', $today)
                            ->whereDate('tgl_akhir_ijin', '<=', $seminggu);
                    } elseif ($request->filter_expired == 'sebulan') {
                        $query->whereDate('tgl_akhir_ijin', '>', $today)
                            ->whereDate('tgl_akhir_ijin', '<=', $sebulan);
                    }
                })
                ->get();
                return Datatables::of($data)
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $searchQuery = Str::lower($request->get('search'));
                            $instance->collection = $instance->collection->filter(function ($data) use ($searchQuery) {
                                if (Str::contains(Str::lower($data['nama_klinik']), $searchQuery)) {
                                    return true;
                                }
                                if (Str::contains(
                                    Str::lower($data['no_anggota']),
                                    $searchQuery
                                )) {
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
                ->editColumn('tgl_akhir_ijin', function ($row) {
                        $today = Carbon::today();
                        $tglAkhir = $row->tgl_akhir_ijin ? Carbon::parse($row->tgl_akhir_ijin) : null;

                        if (!$tglAkhir) {
                            return '<span class="badge badge-secondary">-</span>';
                        }

                        $diff = $today->diffInDays($tglAkhir, false);

                        $warna = 'badge badge-secondary';
                        if ($tglAkhir->isPast()) {
                            $warna = 'badge badge-danger';
                        } elseif ($tglAkhir->isTomorrow()) {
                            $warna = 'badge badge-warning';
                        } elseif ($today->diffInDays($tglAkhir) <= 7) {
                            $warna = 'badge badge-warning';
                        } elseif ($today->diffInDays($tglAkhir) <= 30) {
                            $warna = 'badge badge-success';
                        }

                        return '<span class="'.$warna.'">' . $tglAkhir->format('d-m-Y') . '</span>';
                    })

                    ->rawColumns(['tgl_akhir_ijin', 'action'])
                    ->make(true);
            }
    }


}
