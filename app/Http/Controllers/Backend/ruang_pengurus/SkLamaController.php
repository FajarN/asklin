<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use App\Models\SkLama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class SkLamaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman Utama Arsip SK Lama
     */
    public function index()
    {
        $provinsis = Province::orderBy('name', 'asc')->get();
        $currentYear = (int) date('Y');

        // Statistik ringkas arsip SK lama
        $baseQuery = SkLama::query()->forCurrentUser();
        $totalSk = (clone $baseQuery)->count();
        $totalPusat = (clone $baseQuery)->where('tingkatan', 'pusat')->count();
        $totalDaerah = (clone $baseQuery)->where('tingkatan', 'daerah')->count();
        $totalCabang = (clone $baseQuery)->where('tingkatan', 'cabang')->count();
        
        $totalExpired = (clone $baseQuery)->whereNotNull('tahun_selesai')
            ->where('tahun_selesai', '<', $currentYear)
            ->count();

        $totalAktif = (clone $baseQuery)->whereNotNull('tahun_selesai')
            ->where('tahun_selesai', '>=', $currentYear)
            ->count();

        return view('backend.sk_lama.index', compact(
            'provinsis',
            'totalSk',
            'totalPusat',
            'totalDaerah',
            'totalCabang',
            'totalExpired',
            'totalAktif'
        ));
    }

    /**
     * DataTables AJAX list Arsip SK Lama
     */
    public function list(Request $request)
    {
        $query = SkLama::with(['provinsi', 'kota', 'creator'])
            ->forCurrentUser()
            ->orderBy('id', 'desc');

        // Filter Tingkatan
        if ($request->filled('tingkatan')) {
            $query->where('tingkatan', $request->tingkatan);
        }

        // Filter Status Masa Berlaku (Aktif / Expired)
        $currentYear = (int) date('Y');
        if ($request->filled('status_masa')) {
            if ($request->status_masa === 'expired') {
                $query->where(function ($q) use ($currentYear) {
                    $q->where('tahun_selesai', '<', $currentYear)
                      ->orWhere(function ($sub) use ($currentYear) {
                          $sub->whereNull('tahun_selesai')
                              ->whereRaw("CAST(SUBSTRING_INDEX(periode, '-', -1) AS UNSIGNED) > 1900")
                              ->whereRaw("CAST(SUBSTRING_INDEX(periode, '-', -1) AS UNSIGNED) < ?", [$currentYear]);
                      });
                });
            } elseif ($request->status_masa === 'aktif') {
                $query->where(function ($q) use ($currentYear) {
                    $q->where('tahun_selesai', '>=', $currentYear)
                      ->orWhere(function ($sub) use ($currentYear) {
                          $sub->whereNull('tahun_selesai')
                              ->whereRaw("CAST(SUBSTRING_INDEX(periode, '-', -1) AS UNSIGNED) >= ?", [$currentYear]);
                      });
                });
            }
        }

        // Filter Provinsi
        if ($request->filled('id_provinsi')) {
            $query->where('id_provinsi', $request->id_provinsi);
        }

        // Filter Kota
        if ($request->filled('id_kota')) {
            $query->where('id_kota', $request->id_kota);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tingkatan_badge', function ($row) {
                switch ($row->tingkatan) {
                    case 'pusat':
                        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-950/50 dark:text-red-400 border border-red-200 dark:border-red-900/50"><i class="fas fa-landmark text-[10px]"></i> Pengurus Pusat</span>';
                    case 'daerah':
                        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50"><i class="fas fa-map-marked-alt text-[10px]"></i> Pengda (Provinsi)</span>';
                    case 'cabang':
                        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50"><i class="fas fa-building text-[10px]"></i> Pengcab (Kab/Kota)</span>';
                    default:
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-600">-</span>';
                }
            })
            ->addColumn('wilayah_display', function ($row) {
                if ($row->tingkatan === 'pusat') {
                    return '<span class="font-medium text-slate-800 dark:text-slate-200">Nasional (PP ASKLIN)</span>';
                } elseif ($row->tingkatan === 'daerah') {
                    $prov = $row->provinsi ? $row->provinsi->name : '-';
                    return '<span class="font-medium text-slate-800 dark:text-slate-200">' . htmlspecialchars($prov) . '</span>';
                } elseif ($row->tingkatan === 'cabang') {
                    $kota = $row->kota ? $row->kota->name : '-';
                    $prov = $row->provinsi ? ' <span class="text-xs text-slate-400">(' . htmlspecialchars($row->provinsi->name) . ')</span>' : '';
                    return '<span class="font-medium text-slate-800 dark:text-slate-200">' . htmlspecialchars($kota) . '</span>' . $prov;
                }
                return '-';
            })
            ->addColumn('periode_info', function ($row) {
                $periodeStr = $row->periode ? htmlspecialchars($row->periode) : '-';
                $badge = $row->status_masa_berlaku_badge;
                return '<div class="space-y-1">
                            <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs">' . $periodeStr . '</div>
                            <div>' . $badge . '</div>
                        </div>';
            })
            ->addColumn('file_display', function ($row) {
                if (!$row->file_sk) {
                    return '<span class="text-xs text-slate-400 italic">Tidak ada file</span>';
                }

                $ext = strtolower($row->file_ekstensi ?? pathinfo($row->file_sk, PATHINFO_EXTENSION));
                $isPdf = $ext === 'pdf';
                $iconClass = $isPdf ? 'fa-file-pdf text-red-500' : 'fa-file-word text-blue-600';
                $badgeBg = $isPdf ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-400' : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400';
                $fileNama = $row->file_nama_asli ? htmlspecialchars($row->file_nama_asli) : 'Dokumen SK';
                $downloadUrl = route('sk_lama.download', $row->id);

                return '<div class="flex items-center gap-2">
                            <a href="' . $downloadUrl . '" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border text-xs font-medium hover:opacity-80 transition ' . $badgeBg . '" target="_blank" title="Unduh: ' . $fileNama . '">
                                <i class="fas ' . $iconClass . ' text-sm"></i>
                                <span class="max-w-[150px] truncate">' . strtoupper($ext) . '</span>
                                <i class="fas fa-download text-[10px] ml-1 opacity-70"></i>
                            </a>
                        </div>';
            })
            ->addColumn('action', function ($row) {
                $downloadUrl = route('sk_lama.download', $row->id);
                $btnDownload = '<a href="' . $downloadUrl . '" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition" title="Unduh Berkas"><i class="fas fa-download text-xs"></i></a>';
                $btnEdit = '<button type="button" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-slate-800 rounded-lg transition btn-edit" data-id="' . $row->id . '" title="Edit Arsip SK"><i class="fas fa-edit text-xs"></i></button>';
                // Tombol delete menggunakan icon saja sesuai instruksi
                $btnDelete = '<button type="button" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition btn-delete" data-id="' . $row->id . '" data-judul="' . htmlspecialchars($row->judul) . '" title="Hapus"><i class="fas fa-trash text-xs"></i></button>';

                return '<div class="flex items-center justify-end gap-1">' . $btnDownload . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['tingkatan_badge', 'wilayah_display', 'periode_info', 'file_display', 'action'])
            ->make(true);
    }

    /**
     * Simpan data Arsip SK Lama baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'no_sk' => 'nullable|string|max:255',
            'tingkatan' => 'required|in:pusat,daerah,cabang',
            'id_provinsi' => 'required_if:tingkatan,daerah,cabang|nullable|string|max:10',
            'id_kota' => 'required_if:tingkatan,cabang|nullable|string|max:10',
            'periode' => 'required|string|max:100',
            'tahun_mulai' => 'nullable|numeric|digits:4',
            'tahun_selesai' => 'nullable|numeric|digits:4',
            'tgl_penetapan' => 'nullable|date',
            'file_sk' => 'required|file|mimes:pdf,doc,docx|max:30720', // max 30MB
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'judul.required' => 'Judul SK / Perihal wajib diisi.',
            'tingkatan.required' => 'Pilih tingkatan kepengurusan SK.',
            'id_provinsi.required_if' => 'Provinsi wajib dipilih untuk SK Tingkat Daerah/Cabang.',
            'id_kota.required_if' => 'Kabupaten/Kota wajib dipilih untuk SK Tingkat Cabang.',
            'periode.required' => 'Periode masa bakti wajib diisi (contoh: 2019 - 2024).',
            'file_sk.required' => 'File dokumen SK (.pdf, .doc, .docx) wajib diunggah.',
            'file_sk.mimes' => 'Format file harus berupa Dokumen PDF (.pdf) atau Word (.doc, .docx).',
            'file_sk.max' => 'Ukuran file maksimal adalah 30MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Parsing tahun mulai dan tahun selesai jika kosong tapi tertera di string periode (misal "2019 - 2024")
            $tahunMulai = $request->tahun_mulai;
            $tahunSelesai = $request->tahun_selesai;

            if (!$tahunMulai || !$tahunSelesai) {
                if (preg_match('/(\d{4})\s*[-–—/]\s*(\d{4})/', $request->periode, $matches)) {
                    $tahunMulai = $tahunMulai ?: (int) $matches[1];
                    $tahunSelesai = $tahunSelesai ?: (int) $matches[2];
                } elseif (preg_match('/(\d{4})/', $request->periode, $matches)) {
                    $tahunSelesai = $tahunSelesai ?: (int) $matches[1];
                }
            }

            // Upload file
            $file = $request->file('file_sk');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $fileSize = $file->getSize();
            $filename = 'sk_lama_' . time() . '_' . uniqid() . '.' . $extension;

            // Pastikan folder penyimpanan ada
            Storage::disk('public')->makeDirectory('sk_lama');
            $path = $file->storeAs('sk_lama', $filename, 'public');

            $skLama = new SkLama();
            $skLama->judul = $request->judul;
            $skLama->no_sk = $request->no_sk;
            $skLama->tingkatan = $request->tingkatan;
            $skLama->id_provinsi = $request->tingkatan === 'pusat' ? null : $request->id_provinsi;
            $skLama->id_kota = $request->tingkatan === 'cabang' ? $request->id_kota : null;
            $skLama->periode = $request->periode;
            $skLama->tahun_mulai = $tahunMulai;
            $skLama->tahun_selesai = $tahunSelesai;
            $skLama->tgl_penetapan = $request->tgl_penetapan;
            $skLama->file_sk = $filename;
            $skLama->file_nama_asli = $originalName;
            $skLama->file_ekstensi = $extension;
            $skLama->file_size = $fileSize;
            $skLama->keterangan = $request->keterangan;
            $skLama->created_by = Auth::id();
            $skLama->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'History SK berhasil disimpan ke dalam sistem!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan history SK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ambil data Arsip SK Lama untuk Modal Edit
     */
    public function edit($id)
    {
        $skLama = SkLama::with(['provinsi', 'kota'])->findOrFail($id);

        $cities = [];
        if ($skLama->id_provinsi) {
            $cities = City::where('province_code', $skLama->id_provinsi)->orderBy('name', 'asc')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $skLama,
            'cities' => $cities
        ]);
    }

    /**
     * Update Arsip SK Lama
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'no_sk' => 'nullable|string|max:255',
            'tingkatan' => 'required|in:pusat,daerah,cabang',
            'id_provinsi' => 'required_if:tingkatan,daerah,cabang|nullable|string|max:10',
            'id_kota' => 'required_if:tingkatan,cabang|nullable|string|max:10',
            'periode' => 'required|string|max:100',
            'tahun_mulai' => 'nullable|numeric|digits:4',
            'tahun_selesai' => 'nullable|numeric|digits:4',
            'tgl_penetapan' => 'nullable|date',
            'file_sk' => 'nullable|file|mimes:pdf,doc,docx|max:30720', // optional saat edit
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'judul.required' => 'Judul SK / Perihal wajib diisi.',
            'tingkatan.required' => 'Pilih tingkatan kepengurusan SK.',
            'id_provinsi.required_if' => 'Provinsi wajib dipilih untuk SK Tingkat Daerah/Cabang.',
            'id_kota.required_if' => 'Kabupaten/Kota wajib dipilih untuk SK Tingkat Cabang.',
            'periode.required' => 'Periode masa bakti wajib diisi (contoh: 2019 - 2024).',
            'file_sk.mimes' => 'Format file harus berupa Dokumen PDF (.pdf) atau Word (.doc, .docx).',
            'file_sk.max' => 'Ukuran file maksimal adalah 30MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $skLama = SkLama::findOrFail($id);

            // Parsing tahun mulai dan selesai
            $tahunMulai = $request->tahun_mulai;
            $tahunSelesai = $request->tahun_selesai;

            if (!$tahunMulai || !$tahunSelesai) {
                if (preg_match('/(\d{4})\s*[-–—/]\s*(\d{4})/', $request->periode, $matches)) {
                    $tahunMulai = $tahunMulai ?: (int) $matches[1];
                    $tahunSelesai = $tahunSelesai ?: (int) $matches[2];
                } elseif (preg_match('/(\d{4})/', $request->periode, $matches)) {
                    $tahunSelesai = $tahunSelesai ?: (int) $matches[1];
                }
            }

            // Cek jika upload file baru pengganti
            if ($request->hasFile('file_sk')) {
                // Hapus file lama jika ada
                if ($skLama->file_sk && Storage::disk('public')->exists('sk_lama/' . $skLama->file_sk)) {
                    Storage::disk('public')->delete('sk_lama/' . $skLama->file_sk);
                }

                $file = $request->file('file_sk');
                $originalName = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                $fileSize = $file->getSize();
                $filename = 'sk_lama_' . time() . '_' . uniqid() . '.' . $extension;

                $file->storeAs('sk_lama', $filename, 'public');

                $skLama->file_sk = $filename;
                $skLama->file_nama_asli = $originalName;
                $skLama->file_ekstensi = $extension;
                $skLama->file_size = $fileSize;
            }

            $skLama->judul = $request->judul;
            $skLama->no_sk = $request->no_sk;
            $skLama->tingkatan = $request->tingkatan;
            $skLama->id_provinsi = $request->tingkatan === 'pusat' ? null : $request->id_provinsi;
            $skLama->id_kota = $request->tingkatan === 'cabang' ? $request->id_kota : null;
            $skLama->periode = $request->periode;
            $skLama->tahun_mulai = $tahunMulai;
            $skLama->tahun_selesai = $tahunSelesai;
            $skLama->tgl_penetapan = $request->tgl_penetapan;
            $skLama->keterangan = $request->keterangan;
            $skLama->updated_by = Auth::id();
            $skLama->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data History SK berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui history SK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus History SK
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $skLama = SkLama::findOrFail($id);

            // Hapus file fisik di storage jika perlu
            if ($skLama->file_sk && Storage::disk('public')->exists('sk_lama/' . $skLama->file_sk)) {
                Storage::disk('public')->delete('sk_lama/' . $skLama->file_sk);
            }

            $skLama->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'History SK berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus history SK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download Berkas SK
     */
    public function download($id)
    {
        $skLama = SkLama::findOrFail($id);

        if (!$skLama->file_sk || !Storage::disk('public')->exists('sk_lama/' . $skLama->file_sk)) {
            abort(404, 'File dokumen SK tidak ditemukan pada server.');
        }

        $filePath = Storage::disk('public')->path('sk_lama/' . $skLama->file_sk);
        $downloadName = $skLama->file_nama_asli ?: ('SK_' . str_replace(' ', '_', $skLama->judul) . '.' . $skLama->file_ekstensi);

        return response()->download($filePath, $downloadName);
    }
}
