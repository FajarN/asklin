<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sertifikat, Anggota};
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class ExpiredSertifikatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sertifikat', ['only' => ['index', 'store', 'create', 'edit', 'destroy']]);
    }

    public function index(Request $request)
    {
        $anggota = Anggota::when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
            $query->where('id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
            $query->where('id_provinsi', Auth::user()->provinsi);
        })
        ->where('status', 'approved')->latest()->get();
        return view('backend.expired_sertifikat.index', compact('anggota'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $today = now()->startOfDay();
            $besok = now()->addDay()->startOfDay();
            $seminggu = now()->addDays(7)->endOfDay();
            $sebulan = now()->addDays(30)->endOfDay();

            $subquery = DB::table('sertifikat as s1')
                ->select('id_anggota', DB::raw('MAX(dari) as max_dari'))
                ->groupBy('id_anggota');

            $data = Sertifikat::select(
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
                    'a.id_provinsi',
                    DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR) as expired_date')
                )
                ->joinSub($subquery, 'latest_sertifikat', function ($join) {
                    $join->on('sertifikat.id_anggota', '=', 'latest_sertifikat.id_anggota')
                        ->on('sertifikat.dari', '=', 'latest_sertifikat.max_dari');
                })
                ->leftJoin('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
                ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
                ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
                ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
                ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) {
                    $query->where('a.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) {
                    $query->where('a.id_provinsi', Auth::user()->provinsi);
                })
                ->when($request->has('filter_expired') && $request->filter_expired !== '', function ($query) use ($request, $today, $besok, $seminggu, $sebulan) {
                    switch ($request->filter_expired) {
                        case 'expired':
                            $query->where(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), '<', now());
                            break;
                        case 'besok':
                            $query->whereDate(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), '=', now()->addDay()->toDateString());
                            break;
                        case 'seminggu':
                            $query->whereBetween(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), [now(), now()->addDays(7)]);
                            break;
                        case 'sebulan':
                            $query->whereBetween(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), [now(), now()->addDays(30)]);
                            break;
                    }
                })
                ->get();


            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            $search = Str::lower($request->get('search'));
                            return Str::contains(Str::lower($data['nama_klinik']), $search)
                                || Str::contains(Str::lower($data['kota']), $search)
                                || Str::contains(Str::lower($data['no_anggota']), $search);
                        });
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }
    }


}
