<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik, Sertifikat, Pembayaran};
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;
use Auth;

class VerifikasiAnggotaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:verifikasi-anggota', ['only' => ['index', 'list']]);
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
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria'),
                // Tambahkan logika untuk menampilkan tanggal pendaftaran
                \DB::raw('
                    CASE
                        WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                            FROM_UNIXTIME(anggota.created_on)
                        ELSE
                            anggota.created_at
                    END as tanggal_daftar
                ')
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
                // Filter berdasarkan usia data
                ->when($request->get('filter_usia'), function ($query) use ($request) {
                    $filterUsia = $request->get('filter_usia');
                    $tahunSekarang = date('Y');

                    switch ($filterUsia) {
                        case 'tahun_ini':
                            $query->whereRaw('YEAR(CASE
                                WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                                    FROM_UNIXTIME(anggota.created_on)
                                ELSE
                                    anggota.created_at
                            END) = ?', [$tahunSekarang]);
                            break;
                        case '1_tahun':
                            $query->whereRaw('YEAR(CASE
                                WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                                    FROM_UNIXTIME(anggota.created_on)
                                ELSE
                                    anggota.created_at
                            END) = ?', [$tahunSekarang - 1]);
                            break;
                        case '2_tahun':
                            $query->whereRaw('YEAR(CASE
                                WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                                    FROM_UNIXTIME(anggota.created_on)
                                ELSE
                                    anggota.created_at
                            END) = ?', [$tahunSekarang - 2]);
                            break;
                        case '3_tahun':
                            $query->whereRaw('YEAR(CASE
                                WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                                    FROM_UNIXTIME(anggota.created_on)
                                ELSE
                                    anggota.created_at
                            END) = ?', [$tahunSekarang - 3]);
                            break;
                        case '4_tahun_lebih':
                            $query->whereRaw('YEAR(CASE
                                WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                                    FROM_UNIXTIME(anggota.created_on)
                                ELSE
                                    anggota.created_at
                            END) <= ?', [$tahunSekarang - 4]);
                            break;
                    }
                })
                // Filter berdasarkan status
                ->when($request->get('filter_status'), function ($query) use ($request) {
                    $query->where('anggota.status', $request->get('filter_status'));
                })
                // Filter berdasarkan jenis klinik
                ->when($request->get('filter_jenis'), function ($query) use ($request) {
                    $query->where('anggota.jenis_klinik', $request->get('filter_jenis'));
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
                // Format tanggal untuk tampilan dengan warna berdasarkan usia data
                ->addColumn('tanggal_daftar_formatted', function ($row) {
                    if ($row->tanggal_daftar && $row->tanggal_daftar != '0000-00-00 00:00:00') {
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_daftar);
                        $formatTanggal = $tanggal->format('d-m-Y');

                        // Hitung selisih tahun dari sekarang
                        $tahunSekarang = \Carbon\Carbon::now()->year;
                        $tahunData = $tanggal->year;
                        $selisihTahun = $tahunSekarang - $tahunData;

                        // Tentukan warna berdasarkan usia data
                        $warna = '#32CD32'; // Default: masih tahun ini (hijau)

                        if ($selisihTahun >= 4) {
                            $warna = '#8B0000'; // Lebih dari 4 tahun (merah tua)
                        } elseif ($selisihTahun >= 3) {
                            $warna = '#FF0000'; // Lebih dari 3 tahun (merah)
                        } elseif ($selisihTahun >= 2) {
                            $warna = '#FF4500'; // Lebih dari 2 tahun (orange merah)
                        } elseif ($selisihTahun >= 1) {
                            $warna = '#FFA500'; // Lebih dari 1 tahun (orange)
                        }

                        return '<span style="color: ' . $warna . '; font-weight: bold;">' . $formatTanggal . '</span>';
                    }
                    return '<span style="color: #999;">-</span>';
                })
                ->addColumn('action', 'backend.verifikasi_anggota.action')
                ->rawColumns(['action', 'tanggal_daftar_formatted'])
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


    /**
     * Delete anggota yang belum approved dan semua data terkait
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $anggota = Anggota::findOrFail($id);

            // Pastikan hanya anggota yang belum approved yang bisa dihapus
            if ($anggota->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data anggota yang sudah approved tidak dapat dihapus.'
                ], 400);
            }

            // 1. Hapus semua SDM yang terkait dengan klinik ini
            $sdmList = SDM::where('id_klinik', $anggota->id)->get();
            foreach ($sdmList as $sdm) {
                // Hapus file STR jika ada
                if ($sdm->file_str && file_exists(public_path('images/file/' . $sdm->file_str))) {
                    unlink(public_path('images/file/' . $sdm->file_str));
                }
                // Hapus file SIP jika ada
                if ($sdm->file_sip && file_exists(public_path('images/file/' . $sdm->file_sip))) {
                    unlink(public_path('images/file/' . $sdm->file_sip));
                }
                $sdm->delete();
            }

            // 2. Hapus semua data Rumah Sakit terkait
            RumahSakit::where('id_klinik', $anggota->id)->delete();

            // 3. Hapus semua data Asuransi terkait beserta file bukti
            $asuransiList = Asuransi::where('id_klinik', $anggota->id)->get();
            foreach ($asuransiList as $asuransi) {
                if ($asuransi->bukti && file_exists(public_path('images/file/' . $asuransi->bukti))) {
                    unlink(public_path('images/file/' . $asuransi->bukti));
                }
                $asuransi->delete();
            }

            // 4. Hapus semua foto klinik beserta file foto
            $fotoList = FotoKlinik::where('id_klinik', $anggota->id)->get();
            foreach ($fotoList as $foto) {
                if ($foto->foto && file_exists(public_path('images/file/' . $foto->foto))) {
                    unlink(public_path('images/file/' . $foto->foto));
                }
                $foto->delete();
            }

            // 5. Hapus semua pembayaran terkait beserta bukti pembayaran (jika ada)
            if (class_exists('App\Models\Pembayaran')) {
                $pembayaranList = Pembayaran::where('id_anggota', $anggota->id)->get();
                foreach ($pembayaranList as $pembayaran) {
                    if ($pembayaran->bukti && file_exists(public_path('images/file/' . $pembayaran->bukti))) {
                        unlink(public_path('images/file/' . $pembayaran->bukti));
                    }
                    $pembayaran->delete();
                }
            }

            // 6. Hapus semua sertifikat terkait (jika ada)
            if (class_exists('App\Models\Sertifikat')) {
                Sertifikat::where('id_anggota', $anggota->id)->delete();
            }

            // 7. Hapus file logo anggota jika ada
            if ($anggota->logo && file_exists(public_path('images/file/' . $anggota->logo))) {
                unlink(public_path('images/file/' . $anggota->logo));
            }

            // 8. Hapus file SIO anggota jika ada
            if ($anggota->sio && file_exists(public_path('images/file/' . $anggota->sio))) {
                unlink(public_path('images/file/' . $anggota->sio));
            }

            // 9. Terakhir hapus data anggota
            $anggota->delete();

            DB::commit();

            \Log::info('Data anggota berhasil dihapus', ['id' => $id, 'nama_klinik' => $anggota->nama_klinik]);

            return response()->json([
                'success' => true,
                'message' => 'Data anggota dan semua data terkait berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Error saat menghapus data anggota: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Konfirmasi delete dengan menampilkan detail yang akan dihapus
     */
    public function confirmDelete($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);

            // Pastikan hanya anggota yang belum approved yang bisa dihapus
            if ($anggota->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data anggota yang sudah approved tidak dapat dihapus.'
                ], 400);
            }

            // Hitung jumlah data terkait yang akan ikut terhapus
            $sdmCount = SDM::where('id_klinik', $anggota->id)->count();
            $rsCount = RumahSakit::where('id_klinik', $anggota->id)->count();
            $asuransiCount = Asuransi::where('id_klinik', $anggota->id)->count();
            $fotoCount = FotoKlinik::where('id_klinik', $anggota->id)->count();

            $pembayaranCount = 0;
            $sertifikatCount = 0;

            if (class_exists('App\Models\Pembayaran')) {
                $pembayaranCount = Pembayaran::where('id_anggota', $anggota->id)->count();
            }

            if (class_exists('App\Models\Sertifikat')) {
                $sertifikatCount = Sertifikat::where('id_anggota', $anggota->id)->count();
            }

            return response()->json([
                'anggota' => $anggota,
                'related_data' => [
                    'sdm' => $sdmCount,
                    'rumah_sakit' => $rsCount,
                    'asuransi' => $asuransiCount,
                    'foto_klinik' => $fotoCount,
                    'pembayaran' => $pembayaranCount,
                    'sertifikat' => $sertifikatCount
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saat konfirmasi delete: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
}
