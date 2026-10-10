<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use App\Models\MasterKetuaUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterKetuaUmumController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master-ketua-umum-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:master-ketua-umum-create', ['only' => ['store']]);
        $this->middleware('permission:master-ketua-umum-edit', ['only' => ['edit', 'setAktif']]);
        $this->middleware('permission:master-ketua-umum-delete', ['only' => ['destroy']]);
    }

    /**
     * Halaman Utama Pengaturan Ketua Umum
     */
    public function index()
    {
        $ketumAktif = MasterKetuaUmum::getAktif();
        return view('backend.master_ketua_umum.index', compact('ketumAktif'));
    }

    /**
     * DataTables AJAX list Ketua Umum (aktif & riwayat terdahulu)
     */
    public function list(Request $request)
    {
        $query = MasterKetuaUmum::query()->orderByRaw("CASE WHEN status = 'aktif' THEN 0 ELSE 1 END")->orderBy('periode_mulai', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('periode', function ($row) {
                return $row->periode_mulai . ' - ' . $row->periode_selesai;
            })
            ->editColumn('status', function ($row) {
                if ($row->status === 'aktif') {
                    return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Menjabat Aktif</span>';
                }
                return '<span class="badge badge-secondary"><i class="fas fa-history"></i> Demisioner / Riwayat</span>';
            })
            ->addColumn('ttd_preview', function ($row) {
                if ($row->file_ttd && Storage::exists('public/ttd_ketum/' . $row->file_ttd)) {
                    $url = asset('storage/ttd_ketum/' . $row->file_ttd);
                    return '<a href="' . $url . '" target="_blank"><img src="' . $url . '" alt="TTD" style="height: 40px; border: 1px solid #ddd; padding: 2px; border-radius: 4px; background: #fafafa;"></a>';
                }
                return '<span class="text-muted small">Belum diunggah</span>';
            })
            ->addColumn('action', function ($row) {
                $btnEdit = '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '" title="Edit Data"><i class="fas fa-edit"></i> Edit</button>';
                
                $btnAktifkan = '';
                if ($row->status !== 'aktif') {
                    $btnAktifkan = '<button class="btn btn-sm btn-success set-aktif-btn" data-id="' . $row->id . '" data-nama="' . htmlspecialchars($row->nama_lengkap) . '" title="Tetapkan sebagai Ketua Umum Aktif"><i class="fas fa-user-check"></i> Jadikan Aktif</button>';
                }

                $btnDelete = '';
                if ($row->status !== 'aktif') {
                    $btnDelete = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
                }

                return '<div class="btn-group" role="group">' . $btnEdit . $btnAktifkan . $btnDelete . '</div>';
            })
            ->rawColumns(['status', 'ttd_preview', 'action'])
            ->make(true);
    }

    /**
     * Simpan baru atau update Ketua Umum
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'periode_mulai' => 'required|string|max:10',
            'periode_selesai' => 'required|string|max:10',
            'status' => 'required|in:aktif,nonaktif',
            'file_ttd' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $isEdit = $request->has('id') && $request->id;
            $ketum = $isEdit ? MasterKetuaUmum::findOrFail($request->id) : new MasterKetuaUmum();

            // Jika status diset ke 'aktif', nonaktifkan Ketua Umum lainnya
            if ($request->status === 'aktif') {
                MasterKetuaUmum::where('status', 'aktif')
                    ->when($isEdit, function ($q) use ($request) {
                        $q->where('id', '!=', $request->id);
                    })
                    ->update(['status' => 'nonaktif']);
            }

            $ketum->nama_lengkap = $request->nama_lengkap;
            $ketum->periode_mulai = $request->periode_mulai;
            $ketum->periode_selesai = $request->periode_selesai;
            $ketum->status = $request->status;
            $ketum->keterangan = $request->keterangan;

            // Handle upload file tanda tangan
            if ($request->hasFile('file_ttd')) {
                if ($ketum->file_ttd && Storage::exists('public/ttd_ketum/' . $ketum->file_ttd)) {
                    Storage::delete('public/ttd_ketum/' . $ketum->file_ttd);
                }

                $file = $request->file('file_ttd');
                $filename = 'ttd_ketum_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/ttd_ketum', $filename);
                $ketum->file_ttd = $filename;
            }

            if ($isEdit) {
                $ketum->updated_by = auth()->id();
                $ketum->save();
                $message = 'Data Ketua Umum berhasil diperbarui!';
            } else {
                $ketum->created_by = auth()->id();
                $ketum->save();
                $message = 'Ketua Umum baru berhasil ditambahkan!';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Ambil data untuk modal edit
     */
    public function edit($id)
    {
        $ketum = MasterKetuaUmum::findOrFail($id);
        $ttdUrl = ($ketum->file_ttd && Storage::exists('public/ttd_ketum/' . $ketum->file_ttd))
            ? asset('storage/ttd_ketum/' . $ketum->file_ttd)
            : null;

        return response()->json([
            'success' => true,
            'data' => $ketum,
            'ttd_url' => $ttdUrl,
        ]);
    }

    /**
     * Tetapkan Ketua Umum tertentu sebagai yang menjabat aktif
     */
    public function setAktif($id)
    {
        DB::beginTransaction();
        try {
            $ketum = MasterKetuaUmum::findOrFail($id);

            // Nonaktifkan semua Ketua Umum lain
            MasterKetuaUmum::where('id', '!=', $id)->update(['status' => 'nonaktif']);

            // Aktifkan yang dipilih
            $ketum->status = 'aktif';
            $ketum->updated_by = auth()->id();
            $ketum->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Ketua Umum Aktif berhasil diubah ke: ' . $ketum->nama_lengkap,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hapus riwayat Ketua Umum (hanya jika nonaktif dan belum menerbitkan SK)
     */
    public function destroy(Request $request)
    {
        try {
            $ketum = MasterKetuaUmum::findOrFail($request->id);

            if ($ketum->status === 'aktif') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus Ketua Umum yang sedang menjabat aktif!'
                ], 422);
            }

            if ($ketum->skPengurus()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Ketua Umum ini tidak dapat dihapus karena tersimpan sebagai penerbit SK resmi yang sah.'
                ], 422);
            }

            if ($ketum->file_ttd && Storage::exists('public/ttd_ketum/' . $ketum->file_ttd)) {
                Storage::delete('public/ttd_ketum/' . $ketum->file_ttd);
            }

            $ketum->delete();
            return response()->json(['success' => true, 'message' => 'Data riwayat Ketua Umum berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
