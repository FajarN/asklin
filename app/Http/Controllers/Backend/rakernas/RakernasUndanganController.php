<?php

namespace App\Http\Controllers\Backend\rakernas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RakernasUndangan;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RakernasUndanganController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = RakernasUndangan::query();
            if ($request->filled('id_periode')) {
                $query->where('id_periode', $request->id_periode);
            }
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            $data = $query->orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($search) {
                            return Str::contains(Str::lower($row['nama'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['instansi'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['jabatan'] ?? ''), Str::lower($search));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('tokoh_info', function($row){
                    if ($row->foto_logo && file_exists(public_path('storage/' . $row->foto_logo))) {
                        $avatar = '<img src="' . asset('storage/' . $row->foto_logo) . '" alt="' . e($row->nama) . '" class="w-12 h-12 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs flex-shrink-0">';
                    } else {
                        $icons = [
                            'kemenkes' => 'fa-landmark text-red-600 bg-red-50 dark:bg-red-950/50 border-red-200 dark:border-red-900',
                            'bpjs' => 'fa-heartbeat text-blue-600 bg-blue-50 dark:bg-blue-950/50 border-blue-200 dark:border-blue-900',
                            'pemerintah' => 'fa-award text-amber-600 bg-amber-50 dark:bg-amber-950/50 border-amber-200 dark:border-amber-900',
                            'sponsor' => 'fa-hand-holding-medical text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50 border-emerald-200 dark:border-emerald-900',
                            'narasumber' => 'fa-microphone text-purple-600 bg-purple-50 dark:bg-purple-950/50 border-purple-200 dark:border-purple-900',
                        ];
                        $iconClass = $icons[$row->kategori] ?? 'fa-user-tie text-slate-600 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700';
                        $avatar = '<div class="w-12 h-12 rounded-xl border flex items-center justify-center font-bold text-lg shadow-xs flex-shrink-0 ' . $iconClass . '"><i class="fas ' . explode(' ', $iconClass)[0] . '"></i></div>';
                    }

                    return '<div class="flex items-center gap-3">
                                ' . $avatar . '
                                <div>
                                    <div class="font-bold text-sm text-slate-800 dark:text-white leading-snug">' . e($row->nama) . '</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">' . e($row->jabatan ?? '-') . '</div>
                                </div>
                            </div>';
                })
                ->addColumn('kategori_badge', function($row){
                    $colors = [
                        'kemenkes' => 'badge-danger',
                        'bpjs' => 'badge-primary',
                        'pemerintah' => 'badge-warning text-dark',
                        'sponsor' => 'badge-success',
                        'narasumber' => 'badge-purple text-white',
                        'lainnya' => 'badge-secondary',
                    ];
                    $badgeClass = $colors[$row->kategori] ?? 'badge-secondary';
                    return '<div class="space-y-1">
                                <span class="badge ' . $badgeClass . ' px-3 py-1.5 text-xs font-bold uppercase tracking-wider">' . strtoupper($row->kategori) . '</span>
                                <div class="text-xs font-semibold text-slate-600 dark:text-slate-300 mt-1">' . e($row->instansi) . '</div>
                            </div>';
                })
                ->addColumn('status_kehadiran_badge', function($row){
                    if ($row->status_kehadiran == 'Terkonfirmasi') {
                        return '<span class="badge badge-success px-3 py-1.5 text-xs font-bold"><i class="fas fa-check-circle mr-1.5"></i>Terkonfirmasi</span>';
                    } elseif ($row->status_kehadiran == 'Berhalangan') {
                        return '<span class="badge badge-danger px-3 py-1.5 text-xs font-bold"><i class="fas fa-times-circle mr-1.5"></i>Berhalangan</span>';
                    }
                    return '<span class="badge badge-warning px-3 py-1.5 text-xs font-bold text-dark"><i class="fas fa-clock mr-1.5"></i>Dalam Konfirmasi</span>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" class="btn btn-outline-info btn-sm px-2.5 py-1.5" onclick="editUndangan('.$row->id.')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5" onclick="deleteUndangan('.$row->id.')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['tokoh_info', 'kategori_badge', 'status_kehadiran_badge', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_periode' => 'required',
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'kategori' => 'required',
        ]);

        $data = [
            'id_periode' => $request->id_periode,
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'jabatan' => $request->jabatan,
            'kategori' => $request->kategori,
            'status_kehadiran' => $request->status_kehadiran ?? 'Dalam Konfirmasi',
            'urutan' => $request->urutan ?? 0,
        ];

        if ($request->hasFile('foto_logo')) {
            $fotoPath = $request->file('foto_logo')->store('rakernas/undangan', 'public');
            $data['foto_logo'] = $fotoPath;
        }

        if ($request->filled('id')) {
            $undangan = RakernasUndangan::findOrFail($request->id);
            $undangan->update($data);
            $msg = 'Data pihak yang diundang berhasil diperbarui.';
        } else {
            RakernasUndangan::create($data);
            $msg = 'Pihak yang diundang berhasil ditambahkan.';
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $data = RakernasUndangan::findOrFail($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $undangan = RakernasUndangan::findOrFail($request->id);
        if ($undangan->foto_logo) {
            Storage::disk('public')->delete($undangan->foto_logo);
        }
        $undangan->delete();
        return response()->json(['success' => true, 'message' => 'Data pihak yang diundang berhasil dihapus.']);
    }
}

