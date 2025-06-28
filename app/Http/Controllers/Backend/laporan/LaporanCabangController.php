<?php

namespace App\Http\Controllers\Backend\laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Facades\Log;

class LaporanCabangController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporan-cabang', ['only' => ['index', 'list', 'export']]);
    }

    public function index()
    {
        return view('backend.laporan.cabang');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name as kota_name',
                'indonesia_provinces.name as provinsi_name',
                'indonesia_districts.name as kecamatan_name',
                'indonesia_villages.name as kelurahan_name',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
            )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
                ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('anggota.id_kota', Auth::user()->kota);
                })
                ->groupBy('anggota.id')
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', 'backend.verifikasi.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function export(Request $request)
    {
        try {
            // Debug: Log request
            Log::info('Export request received', ['search' => $request->get('search')]);

            // Ambil data yang sama seperti di method list
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name as kota_name',
                'indonesia_provinces.name as provinsi_name',
                'indonesia_districts.name as kecamatan_name',
                'indonesia_villages.name as kelurahan_name',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
            )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
                ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('anggota.id_kota', Auth::user()->kota);
                })
                ->groupBy('anggota.id')
                ->get();

            Log::info('Data count before filter: ' . $data->count());

            // Filter berdasarkan search jika ada
            if ($request->has('search') && !empty($request->search)) {
                $data = $data->filter(function ($item) use ($request) {
                    return Str::contains(Str::lower($item->nama_klinik ?? ''), Str::lower($request->search));
                });
            }

            Log::info('Data count after filter: ' . $data->count());

            // Generate nama file dengan timestamp
            $fileName = 'laporan_anggota_cabang_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Buat temporary file path
            $filePath = storage_path('app/temp/' . $fileName);

            // Pastikan direktori temp ada
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Buat Excel writer
            $writer = SimpleExcelWriter::create($filePath);

            // Tambahkan header sesuai permintaan
            $writer->addHeader([
                'No',
                'Jenis Klinik',
                'No. Anggota',
                'Nama Klinik',
                'Nama Pemilik Klinik',
                'Email',
                'Telepon',
                'Alamat Klinik',
                'Provinsi',
                'Kab/Kota',
                'Kecamatan',
                'Kelurahan'
            ]);

            // Tambahkan data
            $no = 1;
            foreach ($data as $item) {
                $writer->addRow([
                    $no++,
                    $item->jenis_klinik ?? '',
                    $item->no_anggota ?? '',
                    $item->nama_klinik ?? '',
                    $item->nama_pemilik_klinik ?? '',
                    $item->email ?? '',
                    $item->tlf ?? '',
                    $item->alamat_klinik ?? '',
                    $item->provinsi_name ?? '',
                    $item->kota_name ?? '',
                    $item->kecamatan_name ?? '',
                    $item->kelurahan_name ?? ''
                ]);
            }

            // Tutup writer
            $writer->close();

            Log::info('Excel file created: ' . $filePath);

            // Download file dan hapus setelah download
            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Terjadi kesalahan saat export: ' . $e->getMessage()
            ], 500);
        }
    }
}
