<?php

namespace App\Http\Controllers\Backend\rakernas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RakernasPeriode;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Auth;

class RakernasPeriodeController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = RakernasPeriode::withCount('peserta', 'undangan')->orderBy('tahun', 'desc')->orderBy('rakernas_ke', 'asc')->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($search) {
                            return Str::contains(Str::lower($row['nama'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['periode'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['tempat'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['tema'] ?? ''), Str::lower($search));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('rakernas_label', function($row){
                    $keBadge = $row->rakernas_ke == '1' 
                        ? '<span class="badge badge-primary px-2.5 py-1 text-xs font-bold">Rakernas I</span>'
                        : '<span class="badge badge-info px-2.5 py-1 text-xs font-bold">Rakernas II</span>';
                    return '<div class="space-y-1">
                                <div class="font-bold text-sm text-slate-800 dark:text-white">' . e($row->nama) . '</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">' . $keBadge . ' <span class="font-medium">Periode ' . e($row->periode) . ' (Tahun ' . $row->tahun . ')</span></div>
                            </div>';
                })
                ->addColumn('waktu_tempat', function($row){
                    $tgl = $row->tanggal_formatted;
                    $tempat = $row->tempat ? e($row->tempat) : '<span class="text-slate-400 italic">Belum ditentukan</span>';
                    return '<div class="space-y-1">
                                <div class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300"><i class="far fa-calendar-alt text-emerald-600 mr-1.5"></i>' . $tgl . '</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400"><i class="fas fa-map-marker-alt text-red-500 mr-1.5"></i>' . $tempat . '</div>
                            </div>';
                })
                ->addColumn('peserta_count', function($row){
                    return '<span class="badge badge-light border font-bold text-xs px-3 py-1.5"><i class="fas fa-users text-teal-600 mr-1.5"></i>' . $row->peserta_count . ' Peserta</span>';
                })
                ->addColumn('status_badge', function($row){
                    if ($row->status == '1') {
                        return '<button type="button" onclick="toggleStatusPeriode('.$row->id.')" class="btn btn-sm btn-success py-1.5 px-3 rounded-full font-bold shadow-xs inline-flex items-center gap-1.5" title="Klik untuk menonaktifkan">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Publik Aktif
                                </button>';
                    }
                    return '<button type="button" onclick="toggleStatusPeriode('.$row->id.')" class="btn btn-sm btn-secondary py-1.5 px-3 rounded-full font-bold inline-flex items-center gap-1.5" title="Klik untuk mengaktifkan">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Nonaktif
                            </button>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" class="btn btn-outline-info btn-sm px-2.5 py-1.5" onclick="editPeriode('.$row->id.')" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5" onclick="deletePeriode('.$row->id.')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['rakernas_label', 'waktu_tempat', 'peserta_count', 'status_badge', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'rakernas_ke' => 'required|in:1,2',
            'periode' => 'required|string|max:50',
            'tahun' => 'required|numeric',
        ]);

        $data = [
            'nama' => $request->nama,
            'rakernas_ke' => $request->rakernas_ke,
            'periode' => $request->periode,
            'tahun' => $request->tahun,
            'tema' => $request->tema,
            'subtema' => $request->subtema,
            'tempat' => $request->tempat,
            'alamat_venue' => $request->alamat_venue,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tgl_tutup_daftar' => $request->tgl_tutup_daftar,
            'biaya_registrasi' => $request->biaya_registrasi ?? '0',
            'video_url' => $request->video_url,
            'deskripsi' => $request->deskripsi,
            'sambutan_ketum' => $request->sambutan_ketum,
            'status' => $request->status ?? '1',
            'updated_by' => Auth::id(),
        ];

        // Handle upload banner jika ada
        if ($request->hasFile('banner_img')) {
            $bannerPath = $request->file('banner_img')->store('rakernas/banner', 'public');
            $data['banner_img'] = $bannerPath;
        }

        // Handle upload rundown jika ada
        if ($request->hasFile('rundown_file')) {
            $rundownPath = $request->file('rundown_file')->store('rakernas/rundown', 'public');
            $data['rundown_file'] = $rundownPath;
        }

        // Jika status diset '1', nonaktifkan periode lain agar pendaftaran terarah ke yang aktif
        if ($data['status'] === '1') {
            RakernasPeriode::where('status', '1')->update(['status' => '0']);
        }

        if ($request->filled('id')) {
            $periode = RakernasPeriode::findOrFail($request->id);
            $periode->update($data);
            $msg = 'Penyelenggaraan Rakernas berhasil diperbarui.';
        } else {
            $data['created_by'] = Auth::id();
            RakernasPeriode::create($data);
            $msg = 'Penyelenggaraan Rakernas baru berhasil ditambahkan.';
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $data = RakernasPeriode::findOrFail($id);
        return response()->json($data);
    }

    public function toggleStatus(Request $request)
    {
        $periode = RakernasPeriode::findOrFail($request->id);
        if ($periode->status == '1') {
            $periode->status = '0';
            $periode->save();
            return response()->json(['success' => true, 'message' => 'Status Rakernas dinonaktifkan.']);
        } else {
            // Aktifkan satu dan nonaktifkan lainnya
            RakernasPeriode::where('status', '1')->update(['status' => '0']);
            $periode->status = '1';
            $periode->save();
            return response()->json(['success' => true, 'message' => 'Status Rakernas diaktifkan sebagai penyelenggaraan publik saat ini.']);
        }
    }

    public function destroy(Request $request)
    {
        $periode = RakernasPeriode::findOrFail($request->id);
        if ($periode->banner_img) {
            Storage::disk('public')->delete($periode->banner_img);
        }
        if ($periode->rundown_file) {
            Storage::disk('public')->delete($periode->rundown_file);
        }
        $periode->delete();

        return response()->json(['success' => true, 'message' => 'Data penyelenggaraan Rakernas berhasil dihapus.']);
    }
}

